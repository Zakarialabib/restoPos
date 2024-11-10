<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Unit;
use App\Notifications\ExpiryAlert;
use App\Traits\HasExpiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Collection;

class Ingredient extends Model
{
    use HasExpiry;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'unit',
        'conversion_rate',
        'category_id',
        'cost',
        'price',
        'stock',
        'expiry_date',
        'nutritional_info',
        'storage_conditions',
        'supplier_info',
        'instructions',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:2',
        'stock' => 'decimal:2',
        'expiry_date' => 'date',
        'supplier_info' => 'array',
        'instructions' => 'array',
        'nutritional_info' => 'array',
        'storage_conditions' => 'array',
        'unit' => Unit::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ingredient_recipe')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    public function checkExpiry(): void
    {
        $expiryThreshold = now()->addDays(7);
        if ($this->expiry_date && $this->expiry_date <= $expiryThreshold) {
            $this->notify(new ExpiryAlert($this));
        }
    }

    public function updateStock(float $quantity, string $reason = 'Manual Update'): void
    {
        $oldStock = $this->stock;
        $this->stock += $quantity;
        $this->save();

        // Optional: Log stock changes
        StockLog::create([
            'ingredient_id' => $this->id,
            'old_stock' => $oldStock,
            'new_stock' => $this->stock,
            'change' => $quantity,
            'reason' => $reason
        ]);
    }

    public function composables()
    {
        return $this->belongsToMany(Composable::class, 'composable_ingredient');
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function getFormattedStockAttribute()
    {
        return $this->stock . ' units';
    }


    public function setStockAttribute($value): void
    {
        $this->attributes['stock'] = max(0, $value); // Ensure stock is not negative
    }

    public function hasEnoughStock(float $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    // Add a method to calculate nutritional information based on quantity
    public function calculateNutritionalInfo(float $quantity): array
    {
        $nutritionalInfo = $this->nutritional_info;
        return [
            'calories' => ($nutritionalInfo['calories'] ?? 0) * $quantity / 100,
            'protein' => ($nutritionalInfo['protein'] ?? 0) * $quantity / 100,
            'carbs' => ($nutritionalInfo['carbs'] ?? 0) * $quantity / 100,
            'fat' => ($nutritionalInfo['fat'] ?? 0) * $quantity / 100,
        ];
    }

    protected function cost(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currentPrice = $this->getCurrentPrice();
                return $currentPrice ? $currentPrice->cost : $this->attributes['cost'] ?? 0;
            }
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currentPrice = $this->getCurrentPrice();
                return $currentPrice ? $currentPrice->price : $this->attributes['price'] ?? 0;
            }
        );
    }

    // New method to reduce stock based on product requirements
    public function reduceStockForProduct(Product $product, int $quantity): bool
    {
        // Get the pivot relationship to determine ingredient quantity per product
        $pivotQuantity = $product->ingredients()
            ->where('ingredient_id', $this->id)
            ->first()?->pivot->stock ?? 0;

        // Calculate total ingredient reduction
        $totalReduction = $pivotQuantity * $quantity;

        // Check if there's enough stock
        if ($this->stock < $totalReduction) {
            return false;
        }

        // Reduce stock
        $this->decrement('stock', $totalReduction);

        return true;
    }

    // Method to check if stock is sufficient for a product order
    public function isStockSufficientForProduct(Product $product, int $quantity): bool
    {
        $pivotQuantity = $product->ingredients()
            ->where('ingredient_id', $this->id)
            ->first()?->pivot->stock ?? 0;

        $totalRequired = $pivotQuantity * $quantity;

        return $this->stock >= $totalRequired;
    }

    public function decrementStock(float $quantity): void
    {
        if (!$this->hasEnoughStock($quantity)) {
            throw new \Exception("Insufficient stock for ingredient: {$this->name}");
        }

        $this->decrement('stock', $quantity);

        // if ($this->isLowStock()) {
        //     InventoryAlert::create([
        //         'ingredient_id' => $this->id,
        //         'message' => "Low stock alert for {$this->name}",
        //     ]);
        // }
    }

    // morph many prices
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function getCurrentPrice(): ?Price
    {
        return $this->prices()
            ->where('date', '<=', now())
            ->latest('date')
            ->first();
    }

    public function addPrice($cost, $price, $notes = null): Price
    {
        return $this->prices()->create([
            'cost' => $cost,
            'price' => $price,
            'date' => now(),
            'notes' => $notes,
        ]);
    }

    public function getPriceHistory(?\DateTime $startDate = null, ?\DateTime $endDate = null): Collection
    {
        $query = $this->prices();

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->orderBy('date', 'desc')->get();
    }
}
