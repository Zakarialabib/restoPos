<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\HasPricing;
use App\Enums\UnitType;
use App\Notifications\ExpiryAlert;
use App\Traits\HasExpiry;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Number;

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
        'is_composable',
        'lead_time',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:2',
        'stock' => 'integer',
        'expiry_date' => 'date',
        'supplier_info' => 'array',
        'instructions' => 'array',
        'nutritional_info' => 'array',
        'storage_conditions' => 'array',
        'unit' => UnitType::class,
        'is_composable' => 'boolean',
        'lead_time' => 'integer',
    ];

    public function category(): BelongsTo
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

    public function composables(): BelongsToMany
    {
        return $this->belongsToMany(Composable::class, 'composable_ingredient');
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function checkExpiry(): void
    {
        $expiryThreshold = now()->addDays(7);
        if ($this->expiry_date && $this->expiry_date <= $expiryThreshold) {
            $this->notify(new ExpiryAlert($this));
        }
    }

    public function updateStock(int $quantity, string $reason = 'Manual Update'): void
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

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function stock(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Number::format($value, locale: 'fr_MA'),
            set: fn (int $value) => max(0, $value),
        );
    }

    public function hasEnoughStock(float $quantity): bool
    {
        return $this->stock >= $quantity;
    }

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

    public function reduceStockForProduct(Product $product, int $quantity): bool
    {
        $pivotQuantity = $product->ingredients()
            ->where('ingredient_id', $this->id)
            ->first()?->pivot->quantity ?? 0;

        $totalReduction = $pivotQuantity * $quantity;

        if ($this->stock < $totalReduction) {
            return false;
        }

        $this->decrement('stock', $totalReduction);

        return true;
    }

    public function isStockSufficientForProduct(Product $product, int $quantity): bool
    {
        $pivotQuantity = $product->ingredients()
            ->where('ingredient_id', $this->id)
            ->first()?->pivot->quantity ?? 0;

        $totalRequired = $pivotQuantity * $quantity;

        return $this->stock >= $totalRequired;
    }

    public function decrementStock(float $quantity): void
    {
        if ( ! $this->hasEnoughStock($quantity)) {
            throw new Exception("Insufficient stock for ingredient: {$this->name}");
        }

        $this->decrement('stock', $quantity);
    }

    public function getCurrentPrice(): ?Price
    {
        return $this->prices()
            ->where('date', '<=', now())
            ->latest('date')
            ->first();
    }

    public function addPrice($cost, $price, $date = null, $notes = null): Price
    {
        return $this->prices()->create([
            'cost' => $cost,
            'price' => $price,
            'date' => $date ?? now(),
            'notes' => $notes,
        ]);
    }

    public function getPriceHistory(?DateTime $startDate = null, ?DateTime $endDate = null): Collection
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

    public function isLowStock(): bool
    {
        return $this->stock < 10;
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
}
