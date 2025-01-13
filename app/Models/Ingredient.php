<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasInventory;
use App\Models\Traits\HasPricing;
use App\Support\HasAdvancedFilter;
use App\Traits\HasExpiry;
use App\Enums\IngredientType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Ingredient extends Model
{
    use HasInventory;
    use HasPricing;
    use SoftDeletes;
    use HasUuids;
    use HasAdvancedFilter;
    use HasExpiry;

    protected const ATTRIBUTES = [
        'id',
        'sku',
        'name',
        'category_id',
        'type',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'category_id',
        'type',
        'image',
        'status',
        'stock_quantity',
        'reorder_point',
        'unit',
        'cost',
        'expiry_date',
        'storage_location',
        'supplier_info',
        'is_seasonal',
        'is_composable',
        'allergens',
        'nutritional_info',
        'preparation_instructions',
        'minimum_order_quantity',
        'lead_time_days',
        'stock_status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'stock_quantity' => 'float',
        'reorder_point' => 'float',
        'cost' => 'float',
        'expiry_date' => 'datetime',
        'entry_date' => 'datetime',
        'supplier_info' => 'array',
        'deleted_at' => 'datetime',
        'is_seasonal' => 'boolean',
        'is_composable' => 'boolean',
        'allergens' => 'array',
        'nutritional_info' => 'array',
        'preparation_instructions' => 'array',
        'minimum_order_quantity' => 'float',
        'lead_time_days' => 'integer',
        'type' => IngredientType::class,
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', DB::raw('reorder_point'));
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30));
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', true)
            ->where('stock_quantity', '>', 0)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            });
    }

    // Helper Methods
    public function isAvailable(): bool
    {
        if (!$this->status || $this->stock_quantity <= 0) {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date <= now()) {
            return false;
        }

        return true;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isBetween(now(), now()->addDays($days));
    }

    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date <= now();
    }

    public function lowStock()
    {
        return $this->where('stock_quantity', '<=', DB::raw('reorder_point'));
    }

    public function calculateWastage(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        return $this->stockLogs()
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where('reason', 'like', '%waste%')
            ->sum('adjustment');
    }

    public function calculateWastagePercentage(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        $totalStock = $this->stockLogs()
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where('adjustment', '>', 0)
            ->sum('adjustment');

        if ($totalStock <= 0) {
            return 0;
        }

        $wastage = $this->calculateWastage($startDate, $endDate);
        return abs($wastage / $totalStock) * 100;
    }

    public function calculateTurnoverRate(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        // Get the total usage (negative adjustments excluding waste)
        $totalUsage = abs($this->stockLogs()
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where('adjustment', '<', 0)
            ->where('reason', 'not like', '%waste%')
            ->sum('adjustment'));

        // Get the average inventory for the period
        $averageInventory = $this->calculateAverageInventory($startDate, $endDate);

        if ($averageInventory <= 0) {
            return 0;
        }

        // Calculate turnover rate (total usage / average inventory)
        return $totalUsage / $averageInventory;
    }

    public function calculateAverageInventory(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        $query = $this->stockLogs();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $stockLevels = $query->orderBy('created_at')
            ->get(['new_quantity', 'created_at']);

        if ($stockLevels->isEmpty()) {
            return $this->stock_quantity;
        }

        $totalStock = 0;
        $previousQuantity = $stockLevels->first()->new_quantity;
        $previousDate = $stockLevels->first()->created_at;

        foreach ($stockLevels as $log) {
            $daysBetween = $previousDate->diffInDays($log->created_at);
            $totalStock += $previousQuantity * $daysBetween;
            
            $previousQuantity = $log->new_quantity;
            $previousDate = $log->created_at;
        }

        $totalDays = $startDate && $endDate
            ? Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate))
            : $stockLevels->first()->created_at->diffInDays($stockLevels->last()->created_at);

        return $totalDays > 0 ? $totalStock / $totalDays : $previousQuantity;
    }

    public function calculateUsageRate(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        $query = $this->stockLogs();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalUsage = abs($query->where('adjustment', '<', 0)
            ->where('reason', 'not like', '%waste%')
            ->sum('adjustment'));

        $days = $startDate && $endDate
            ? Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate))
            : 30; // Default to 30 days if no date range specified

        return $days > 0 ? $totalUsage / $days : 0;
    }

    public function calculateStockEfficiency(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): float
    {
        $turnoverRate = $this->calculateTurnoverRate($startDate, $endDate);
        $wastagePercentage = $this->calculateWastagePercentage($startDate, $endDate);
        
        // Higher turnover and lower wastage = better efficiency
        // Normalize turnover rate (assuming 12 is a good annual turnover rate)
        $normalizedTurnover = min(1, $turnoverRate / 12);
        
        // Normalize wastage (0% wastage = 1, 100% wastage = 0)
        $normalizedWastage = 1 - ($wastagePercentage / 100);
        
        // Calculate efficiency score (50% weight to each factor)
        return (($normalizedTurnover * 0.5) + ($normalizedWastage * 0.5)) * 100;
    }
}
