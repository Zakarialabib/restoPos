<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductType: string
{
    case REGULAR = 'regular';
    case COMPOSABLE = 'composable';
    case INGREDIENT = 'ingredient';
    case ADDON = 'addon';
    case SIDE = 'side';
    case DRINK = 'drink';
    case DESSERT = 'dessert';

    public function label(): string
    {
        return match($this) {
            self::REGULAR => 'Regular Item',
            self::COMPOSABLE => 'Composable Item',
            self::INGREDIENT => 'Ingredient',
            self::ADDON => 'Add-on',
            self::SIDE => 'Side Item',
            self::DRINK => 'Drink',
            self::DESSERT => 'Dessert',
        };
    }

    public function isComposable(): bool
    {
        return self::COMPOSABLE === $this;
    }

    public function isIngredient(): bool
    {
        return self::INGREDIENT === $this;
    }

    public function isAddon(): bool
    {
        return self::ADDON === $this;
    }

    public function isSide(): bool
    {
        return self::SIDE === $this;
    }

    public function isDrink(): bool
    {
        return self::DRINK === $this;
    }

    public function isDessert(): bool
    {
        return self::DESSERT === $this;
    }
}
