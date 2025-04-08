<?php

declare(strict_types=1);

namespace App\Enums;

enum CategoryType: string
{
    case FRUIT = 'fruit';
    case BASE = 'base';
    case INGREDIENT = 'ingredient';
    case COMPOSABLE = 'composable';
    case PRODUCT = 'product';
    case ADDON = 'addon';
    case SUGAR = 'sugar';
    case SIZE = 'size';

    public static function forSelect(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($type) => [
            $type->value => $type->label()
        ])->toArray();
    }

    public function label(): string
    {
        return match($this) {
            self::FRUIT => __('Fruits'),
            self::BASE => __('Base Ingredients'),
            self::INGREDIENT => __('General Ingredients'),
            self::COMPOSABLE => __('Composable Items'),
            self::PRODUCT => __('Finished Products'),
            self::ADDON => __('Additional Items'),
            self::SUGAR => __('Sugar Levels'),
            self::SIZE => __('Portion Sizes'),
        };
    }

    public function color(): array
    {
        return match($this) {
            self::FRUIT => ['bg' => 'emerald-100', 'text' => 'emerald-800', 'icon' => 'local_florist'],
            self::BASE => ['bg' => 'sky-100', 'text' => 'sky-800', 'icon' => 'water_drop'],
            self::INGREDIENT => ['bg' => 'amber-100', 'text' => 'amber-800', 'icon' => 'restaurant'],
            self::COMPOSABLE => ['bg' => 'violet-100', 'text' => 'violet-800', 'icon' => 'extension'],
            self::PRODUCT => ['bg' => 'rose-100', 'text' => 'rose-800', 'icon' => 'shopping_cart'],
            self::ADDON => ['bg' => 'orange-100', 'text' => 'orange-800', 'icon' => 'add_circle'],
            self::SUGAR => ['bg' => 'pink-100', 'text' => 'pink-800', 'icon' => 'bubble_chart'],
            self::SIZE => ['bg' => 'blue-100', 'text' => 'blue-800', 'icon' => 'format_size'],
        };
    }

    public function description(): string
    {
        return match($this) {
            self::FRUIT => __('Fruits are the main ingredients of a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::BASE => __('Base ingredients are the main components of a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::INGREDIENT => __('General ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::COMPOSABLE => __('Composable items are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::PRODUCT => __('Finished products are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::ADDON => __('Additional items are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SUGAR => __('Sugar levels are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SIZE => __('Portion sizes are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
        };
    }

    // google material icon name
    public function icon(): string
    {
        return match($this) {
            self::FRUIT => 'local_florist',
            self::BASE => 'water_drop',
            self::INGREDIENT => 'restaurant',
            self::COMPOSABLE => 'extension',
            self::PRODUCT => 'shopping_cart',
            self::ADDON => 'add_circle',
            self::SUGAR => 'bubble_chart',
            self::SIZE => 'format_size',
        };
    }

}
