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

    public function updateStock(float $quantity): void
    {
        $this->stock += $quantity;
        $this->save();
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
            get: fn (int $value) => Number::currency($value, in: 'MAD', locale: 'fr_MA'),
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Number::currency($value, in: 'MAD', locale: 'fr_MA'),
        );
    }
}
