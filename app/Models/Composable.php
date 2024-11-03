<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

class Composable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'type', // coffee, juice, dried_fruits
        'is_available',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'composable_ingredient')
            ->withTimestamps();
    }

    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function calculateTotalPrice(): float
    {
        return $this->products->sum('price');
    }

    // Add price calculation based on ingredients
    public function calculatePrice(): float
    {
        $basePrice = $this->base_price;
        $ingredientsCost = $this->ingredients->sum(fn ($ingredient) => $ingredient->cost_per_unit * $ingredient->pivot->quantity);

        return $basePrice + ($ingredientsCost * $this->profit_margin);
    }

    // Add stock validation
    public function validateStock(): bool
    {
        return $this->ingredients->every(fn ($ingredient) => $ingredient->hasEnoughStock($ingredient->pivot->quantity));
    }

    // Add nutritional information
    public function getNutritionalInfo(): array
    {
        return $this->ingredients->reduce(function ($carry, $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            return [
                'calories' => $carry['calories'] + ($ingredient->calories * $quantity),
                'protein' => $carry['protein'] + ($ingredient->protein * $quantity),
                'carbs' => $carry['carbs'] + ($ingredient->carbs * $quantity),
                'fat' => $carry['fat'] + ($ingredient->fat * $quantity),
            ];
        }, ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0]);
    }

    // Add ingredient compatibility check
    public function validateIngredientCompatibility(Collection $ingredients): bool
    {
        // Check if ingredients can be combined
        $types = $ingredients->pluck('type')->unique();

        return match($this->type) {
            'juice' => $this->validateJuiceIngredients($types),
            'coffee' => $this->validateCoffeeIngredients($types),
            'dried_fruits' => $this->validateDriedFruitsIngredients($types),
            default => false
        };
    }

    // Methods
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->price, in: 'MAD', locale: 'fr_MA'),
        );
    }

    protected function validateJuiceIngredients(Collection $types): bool
    {
        return $types->contains('fruit') && $types->contains('liquid');
    }
}
