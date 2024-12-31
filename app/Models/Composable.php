<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Composable extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'status',
        'category_id',
        'preparation_instructions',
        'preparation_time',
        'portion_size',
        'portion_unit',
        'nutritional_info',
        'allergens'
    ];

    protected $casts = [
        'status' => 'boolean',
        'preparation_instructions' => 'array',
        'nutritional_info' => 'array',
        'allergens' => 'array',
        'portion_size' => 'float',
        'portion_unit' => UnitType::class
    ];

    // Interface Implementation Methods
    public function getBaseUnit(): UnitType
    {
        return UnitType::Milliliter;
    }

    public function getPortionSize(): float
    {
        return $this->portion_size;
    }

    public function setPortionSize(float $size, UnitType $unit): void
    {
        $this->portion_size = $this->convertToBaseUnit($size, $unit);
        $this->portion_unit = $this->getBaseUnit();
        $this->save();
    }

    public function convertToBaseUnit(float $value, UnitType $fromUnit): float
    {
        if ($fromUnit === $this->getBaseUnit()) {
            return $value;
        }

        return match($fromUnit) {
            UnitType::Liter => $value * 1000,
            UnitType::Milliliter => $value,
            default => throw new \InvalidArgumentException("Unsupported unit conversion")
        };
    }

    public function convertFromBaseUnit(float $value, UnitType $toUnit): float
    {
        if ($toUnit === $this->getBaseUnit()) {
            return $value;
        }

        return match($toUnit) {
            UnitType::Liter => $value / 1000,
            UnitType::Milliliter => $value,
            default => throw new \InvalidArgumentException("Unsupported unit conversion")
        };
    }

    public function validatePortionSize(float $size, UnitType $unit): bool
    {
        $sizeInBase = $this->convertToBaseUnit($size, $unit);
        return $sizeInBase >= 100 && $sizeInBase <= 1000; // Min 100ml, Max 1L
    }

    public function getStandardPortions(): array
    {
        return [
            'small' => ['size' => 250, 'unit' => UnitType::Milliliter],
            'medium' => ['size' => 400, 'unit' => UnitType::Milliliter],
            'large' => ['size' => 600, 'unit' => UnitType::Milliliter],
        ];
    }

    public function calculateNutritionForPortion(float $portion): array
    {
        $baseNutrition = $this->nutritional_info;
        $ratio = $portion / $this->getPortionSize();
        
        return array_map(fn($value) => $value * $ratio, $baseNutrition);
    }

    // Pricing Interface Implementation
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function getCurrentPrice(): float
    {
        return $this->base_price;
    }

    public function setPrice(float $price): void
    {
        $this->base_price = $price;
        $this->save();
        
        // Record price history
        $this->prices()->create([
            'amount' => $price,
            'effective_date' => now()
        ]);
    }

    public function getPriceHistory(): Collection
    {
        return $this->prices()->orderBy('effective_date', 'desc')->get();
    }

    public function calculateProfit(): float
    {
        $costPrice = $this->ingredients->sum(function ($ingredient) {
            return $ingredient->pivot->quantity * $ingredient->price_per_ml;
        });
        
        return $this->getCurrentPrice() - $costPrice;
    }

    public function calculateProfitMargin(): float
    {
        $profit = $this->calculateProfit();
        $price = $this->getCurrentPrice();
        
        return $price > 0 ? ($profit / $price) * 100 : 0;
    }

    // Existing Relationships
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot(['quantity', 'unit', 'is_optional', 'order'])
            ->withTimestamps();
    }

}
