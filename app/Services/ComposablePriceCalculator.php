<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Composable;
use App\Models\Ingredient;
use App\Models\Product;
use App\Contracts\HasPricing;

class ComposablePriceCalculator
{
    public function __construct(
        private readonly Composable $composable
    ) {
    }

    public function calculate(
        array $selectedItems,
        ?string $selectedBase,
        array $selectedAddons,
        string $size,
        string $type,
    ): float {
        return match ($type) {
            'juice' => $this->calculateJuicePrice($selectedItems, $selectedBase, $selectedAddons, $size),
            'salade' => $this->calculateSaladePrice($selectedItems, $selectedBase, $selectedAddons, $size),
            'dried_fruits' => $this->calculateDriedFruitsPrice($selectedItems, $selectedAddons, $size),
            default => 0
        };
    }

    private function calculateJuicePrice(array $fruits, ?string $base, array $addons, string $size): float
    {
        $total = 0;

        // Calculate portions and prices for fruits
        $fruitCount = count($fruits);
        $fruitPortion = $this->composable->getPortionByType('fruit', $fruitCount);

        foreach ($fruits as $fruitId) {
            $fruit = Ingredient::find($fruitId);
            if ($fruit instanceof HasPricing) {
                $total += $fruit->calculatePortionPrice('fruit', $fruitPortion);
            }
        }

        // Add base price if selected
        if ($base) {
            $baseIngredient = Ingredient::where('name', $base)->first();
            if ($baseIngredient instanceof HasPricing) {
                $basePortion = $this->composable->getPortionByType('base', $fruitCount);
                $total += $baseIngredient->calculatePortionPrice('base', $basePortion);
            }
        }

        // Add addons price
        foreach ($addons as $addon) {
            $addonIngredient = Ingredient::where('name', $addon)->first();
            if ($addonIngredient instanceof HasPricing) {
                $total += $addonIngredient->calculatePortionPrice('addon', 0.1);
            }
        }

        return round($total, 2);
    }

    private function calculateSaladePrice(array $salades, ?string $base, array $addons, string $size): float
    {
        $total = 0;

        foreach ($salades as $saladeId) {
            $salade = Product::findOrFail($saladeId);
            $total += $salade->calculatePrice();
        }

        // Add base and addons
        if ($base) {
            $total += 5; // Base price for salade base
        }

        $total += count($addons) * 3; // Addon price

        return round($total, 2);
    }

    private function calculateDriedFruitsPrice(array $driedFruits, array $addons, string $size): float
    {
        $total = 0;

        foreach ($driedFruits as $fruitId) {
            $driedFruit = Product::findOrFail($fruitId);
            $total += $driedFruit->calculatePrice();
        }

        // Add addons price
        foreach ($addons as $addon) {
            $addonIngredient = Ingredient::where('name', $addon)->first();
            if ($addonIngredient instanceof HasPricing) {
                $total += $addonIngredient->calculatePortionPrice('addon', 0.1);
            }
        }

        return round($total, 2);
    }
}
