<?php

declare(strict_types=1);

namespace App\Enums;

enum UnitType: string
{
    case Piece = 'piece';
    case Gram = 'g';
    case Kilogram = 'kg';
    case Milliliter = 'ml';
    case Liter = 'l';
    case Ounce = 'oz';
    case Pound = 'lb';
    case Cup = 'cup';
    case Tablespoon = 'tbsp';
    case Teaspoon = 'tsp';
    case Unit = 'unit';

    public static function forIngredientType(string $type): array
    {
        return match($type) {
            'fruit', 'vegetable' => [
                self::Piece,
                self::Gram,
                self::Kilogram,
                self::Ounce,
                self::Pound,
            ],
            'liquid' => [
                self::Milliliter,
                self::Liter,
                self::Cup,
                self::Tablespoon,
                self::Teaspoon,
            ],
            'spice', 'supplement' => [
                self::Gram,
                self::Teaspoon,
                self::Tablespoon,
            ],
            default => [self::Piece],
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Piece => 'Piece',
            self::Gram => 'Gram',
            self::Kilogram => 'Kilogram',
            self::Milliliter => 'Milliliter',
            self::Liter => 'Liter',
            self::Ounce => 'Ounce',
            self::Pound => 'Pound',
            self::Cup => 'Cup',
            self::Tablespoon => 'Tablespoon',
            self::Teaspoon => 'Teaspoon',
        };
    }

    public function abbreviation(): string
    {
        return $this->value;
    }

    public function baseUnit(): self
    {
        return match($this) {
            self::Kilogram => self::Gram,
            self::Liter => self::Milliliter,
            self::Pound => self::Ounce,
            default => $this,
        };
    }

    public function conversionFactor(): float
    {
        return match($this) {
            self::Kilogram => 1000.0, // 1 kg = 1000 g
            self::Liter => 1000.0, // 1 l = 1000 ml
            self::Pound => 16.0, // 1 lb = 16 oz
            self::Cup => 236.588, // 1 cup = 236.588 ml
            self::Tablespoon => 14.787, // 1 tbsp = 14.787 ml
            self::Teaspoon => 4.929, // 1 tsp = 4.929 ml
            default => 1.0,
        };
    }

    public function convertTo(self $targetUnit, float $value): float
    {
        if ($this === $targetUnit) {
            return $value;
        }

        $baseValue = $value * $this->conversionFactor();
        return $baseValue / $targetUnit->conversionFactor();
    }
}
