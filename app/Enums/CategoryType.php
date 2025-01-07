<?php

namespace App\Enums;

enum CategoryType: string
{
    case INGREDIENT = 'ingredient';
    case PRODUCT = 'product';
    case RECIPE = 'recipe';
    case COMPOSABLE = 'composable';

    public function label(): string
    {
        return match ($this) {
            self::INGREDIENT => 'ingredient',
            self::PRODUCT => 'product',
            self::RECIPE => 'Completed',
            self::COMPOSABLE => 'composable',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INGREDIENT => 'ingredient',
            self::PRODUCT => 'product',
            self::RECIPE => 'Completed',
            self::COMPOSABLE => 'composable',
        };
    }
}
