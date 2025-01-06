<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UnitType;
use App\Traits\HasPrices;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Ingredient extends Model
{
    use HasFactory;
    use HasPrices;
  //  use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'stock_quantity',
        'unit',
        'cost_per_unit',
        'reorder_point',
        'status',
        'is_seasonal',
        'image',
        'nutritional_info',
        'is_composable',
        'popularity',
        'portion_size',
        'portion_unit'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'stock_quantity' => 'decimal:2',
        'reorder_point' => 'decimal:2',
        'nutritional_info' => 'array',
        'popularity' => 'integer',
        'is_composable' => 'boolean',
        'is_seasonal' => 'boolean',
        'portion_size' => 'decimal:2',
        'status' => 'boolean',
        'expiry_date' => 'datetime',
        'storage_conditions' => 'array',
        'supplier_info' => 'array',
        'minimum_order_quantity' => 'decimal:2',
        'lead_time_days' => 'integer',
        'allergens' => 'array',
        'unit' => UnitType::class,
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

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    // Stock Management Methods
    public function adjustStock(float $quantity, string $reason = 'Manual Adjustment'): void
    {
        DB::transaction(function () use ($quantity, $reason) {
            $oldQuantity = $this->stock_quantity;
            $this->stock_quantity = max(0, $this->stock_quantity + $quantity);
            $this->save();

            $this->stockLogs()->create([
                'adjustment' => $quantity,
                'reason' => $reason,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $this->stock_quantity,
            ]);

            if ($this->isLowStock()) {
                Log::warning("Ingredient {$this->name} is running low on stock. Current quantity: {$this->stock_quantity}");
            }
        });
    }

    public function setStockLevel(float $newQuantity, string $reason = null): void
    {
        $adjustment = $newQuantity - $this->stock_quantity;
        $this->adjustStock($adjustment, $reason ?? 'Stock Level Adjustment');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function isExpiringSoon(int $daysThreshold = 30): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->diffInDays(now()) <= $daysThreshold;
    }

    public function hasExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    public function needsReorder(): bool
    {
        return $this->isLowStock() && $this->status;
    }

    // Price Management Methods
    public function updateCost(float $newCost, ?string $reason = null): void
    {
        DB::transaction(function () use ($newCost, $reason) {
            $oldCost = $this->cost_per_unit;
            $this->cost_per_unit = $newCost;
            $this->save();

            $this->prices()->create([
                'amount' => $newCost,
                'previous_amount' => $oldCost,
                'reason' => $reason,
                'cost' => true,
                'effective_date' => now(),
            ]);

            Log::info("Cost updated for ingredient {$this->name} from {$oldCost} to {$newCost}");
        });
    }

    public function addPrice(float $cost, float $price, ?string $notes = null): void
    {
        DB::transaction(function () use ($cost, $price, $notes) {
            $this->updateCost($cost);
            
            $this->prices()->create([
                'amount' => $price,
                'previous_amount' => $this->getCurrentPrice()?->amount,
                'notes' => $notes,
                'effective_date' => now(),
            ]);
        });
    }

    public function getCurrentPrice(): ?Price
    {
        return $this->prices()
            ->where('is_current', true)
            // ->latest('effective_date')
            ->first();
    }

    // Analytics Methods
    public function getUsageStats(Carbon $startDate, Carbon $endDate): array
    {
        $usage = DB::table('order_items')
            ->join('product_ingredients', 'order_items.product_id', '=', 'product_ingredients.product_id')
            ->where('product_ingredients.ingredient_id', $this->id)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(order_items.quantity * product_ingredients.quantity) as total_quantity_used'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
                DB::raw('AVG(order_items.quantity * product_ingredients.quantity) as average_usage_per_order')
            )
            ->first();

        return [
            'total_quantity_used' => $usage->total_quantity_used ?? 0,
            'total_orders' => $usage->total_orders ?? 0,
            'average_usage_per_order' => $usage->average_usage_per_order ?? 0,
            'stock_turnover_rate' => $this->calculateStockTurnoverRate($startDate, $endDate),
        ];
    }

    public function calculateStockTurnoverRate(Carbon $startDate, Carbon $endDate): float
    {
        $averageInventory = $this->stockLogs()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('new_quantity') ?? $this->stock_quantity;

        if ($averageInventory <= 0) {
            return 0;
        }

        $totalUsed = DB::table('order_items')
            ->join('product_ingredients', 'order_items.product_id', '=', 'product_ingredients.product_id')
            ->where('product_ingredients.ingredient_id', $this->id)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->sum(DB::raw('order_items.quantity * product_ingredients.quantity')) ?? 0;

        return $totalUsed / $averageInventory;
    }

    public function getPriceHistory(int $limit = 10): Collection
    {
        return $this->prices()
            ->where('cost', false)
                // ->latest('effective_date')
            ->limit($limit)
            ->get();
    }

    public function getCostHistory(int $limit = 10): Collection
    {
        return $this->prices()
            ->where('cost', true)
                // ->latest('effective_date')
            ->limit($limit)
            ->get();
    }

    public function getStockHistory(int $limit = 10): Collection
    {
        return $this->stockLogs()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '<=', DB::raw('reorder_point'));
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeExpiringSoon(Builder $query, int $daysThreshold = 30): Builder
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($daysThreshold))
            ->where('expiry_date', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    public function scopeNeedsReorder(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where('stock_quantity', '<=', DB::raw('reorder_point'));
    }

    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('popularity');
    }

    public function scopeSeasonal(Builder $query): Builder
    {
        return $query->where('is_seasonal', true);
    }

    // Helper Methods
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function calculateNutrition(float $quantity): array
    {
        if (!$this->nutritional_info) {
            return [];
        }

        $ratio = $quantity / $this->portion_size;
        return collect($this->nutritional_info)
            ->map(fn($value) => $value * $ratio)
            ->toArray();
    }

    public function incrementPopularity(): void
    {
        $this->increment('popularity');
    }

    public function getStandardizedPortion(): array
    {
        return [
            'size' => $this->portion_size,
            'unit' => $this->portion_unit,
            'nutrition' => $this->nutritional_info,
        ];
    }

    public function isStockSufficientForProduct(Product $product, int $quantity): bool
    {
        $requiredQuantity = $product->ingredients()
            ->where('ingredient_id', $this->id)
            ->first()
            ->pivot
            ->quantity * $quantity;

        return $this->stock_quantity >= $requiredQuantity;
    }

    public function convertUnit(float $quantity, UnitType $fromUnit, UnitType $toUnit): float
    {
        return $fromUnit->convertTo($toUnit, $quantity);
    }

    public function getReorderQuantitySuggestion(): float
    {
        $averageDailyUsage = $this->calculateAverageDailyUsage();
        $suggestedQuantity = $averageDailyUsage * ($this->lead_time_days + 7); // 7 days buffer
        return max($suggestedQuantity, $this->minimum_order_quantity);
    }

    private function calculateAverageDailyUsage(): float
    {
        $thirtyDaysAgo = now()->subDays(30);
        $totalUsed = DB::table('order_items')
            ->join('product_ingredients', 'order_items.product_id', '=', 'product_ingredients.product_id')
            ->where('product_ingredients.ingredient_id', $this->id)
            ->where('order_items.created_at', '>=', $thirtyDaysAgo)
            ->sum(DB::raw('order_items.quantity * product_ingredients.quantity')) ?? 0;

        return $totalUsed / 30;
    }
}
