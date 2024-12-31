<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPrices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Ingredient extends Model
{
    use HasFactory;
    use HasPrices;

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
        'nutritional_info' => 'array',
        'popularity' => 'integer',
        'is_composable' => 'boolean',
        'portion_size' => 'decimal:2',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function juices(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot('portion')
            ->withTimestamps();
    }

    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    // Computed Attributes
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    // Stock Management Methods
    public function adjustStock(float $quantity, string $reason = 'Manual Adjustment'): void
    {
        $this->stock_quantity += $quantity;
        $this->save();

        $this->stockLogs()->create([
            'quantity' => $quantity,
            'reason' => $reason,
            'previous_stock' => $this->stock_quantity - $quantity,
            'new_stock' => $this->stock_quantity
        ]);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    // Nutritional Calculation
    public function calculateNutrition(float $quantity): array
    {
        if ( ! $this->nutritional_info) {
            return [
                'calories' => 0,
                'protein' => 0,
                'carbs' => 0,
                'fat' => 0
            ];
        }

        $multiplier = $quantity / $this->portion_size;

        return [
            'calories' => ($this->nutritional_info['calories'] ?? 0) * $multiplier,
            'protein' => ($this->nutritional_info['protein'] ?? 0) * $multiplier,
            'carbs' => ($this->nutritional_info['carbs'] ?? 0) * $multiplier,
            'fat' => ($this->nutritional_info['fat'] ?? 0) * $multiplier
        ];
    }

    // Composable Methods
    public function incrementPopularity(): void
    {
        $this->increment('popularity');
    }

    public function getStandardizedPortion(): array
    {
        return [
            'size' => $this->portion_size,
            'unit' => $this->portion_unit,
        ];
    }

    // Price-related methods
    public function getCurrentPrice(): ?Price
    {
        return $this->prices()
            ->where('is_current', true)
            // ->latest('effective_date')
            ->first();
    }

    public function addPrice(float $cost, float $price, ?string $notes = null, ?string $date = null): Price
    {
        // Deactivate previous current prices
        $this->prices()->update(['is_current' => false]);

        return $this->prices()->create([
            'cost' => $cost,
            'price' => $price,
            'notes' => $notes,
            'effective_date' => $date ?? now(),
            'is_current' => true
        ]);
    }

    public function getPriceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'priceable');
    }
}
