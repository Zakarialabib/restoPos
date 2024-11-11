<?php

declare(strict_types=1);

namespace App\Enums;

enum RecipeType: string
{
    case FRUIT = 'fruit';
    case LIQUID = 'liquid';
    case SALADE = 'salade';

    public function label(): string
    {
        return match($this) {
            self::FRUIT => __('Fruits'),
            self::LIQUID => __('Liquids'),
            self::SALADE => __('Salade'),
        };
    }
}
