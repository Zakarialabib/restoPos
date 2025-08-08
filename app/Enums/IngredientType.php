<?php

declare(strict_types=1);

namespace App\Enums;

enum IngredientType: string
{
    case FRUIT = 'fruit';
    case BASE = 'base';
    case SUGAR = 'sugar';
    case ADDON = 'addon';
    case PRIMARY = 'primary';
    case SEED = 'seed';
    case LEGUME = 'legume';
    case NUT = 'nut';
    case PROTEIN = 'protein';
    case VEGETABLE = 'vegetable';
    case CONDIMENT = 'condiment';
    case GRAIN = 'grain';
    case BREAD = 'bread';
    case OIL = 'oil';
    case SPICE = 'spice';
    case HERB = 'herb';
    case SYRUP = 'syrup';
    case YOGURT = 'yogurt';
    case TOPPING = 'topping';
    case DRIED_FRUIT = 'dried_fruit';
    case NUTS = 'nuts';
    case SEEDS = 'seeds';

    public static function forSelect(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($type) => [
            $type->value => $type->label()
        ])->toArray();
    }

    public function label(): string
    {
        return match($this) {
            self::FRUIT => __('Fruit'),
            self::BASE => __('Base'),
            self::SUGAR => __('Sugar'),
            self::ADDON => __('Add-on'),
            self::PRIMARY => __('Primary'),
            self::SEED => __('Seed'),
            self::LEGUME => __('Legume'),
            self::NUT => __('Nut'),
            self::PROTEIN => __('Protein'),
            self::VEGETABLE => __('Vegetable'),
            self::CONDIMENT => __('Condiment'),
            self::GRAIN => __('Grain'),
            self::BREAD => __('Bread'),
            self::OIL => __('Oil'),
            self::SPICE => __('Spice'),
            self::HERB => __('Herb'),
            self::SYRUP => __('Syrup'),
            self::YOGURT => __('Yogurt'),
            self::TOPPING => __('Topping'),
            self::DRIED_FRUIT => __('Dried Fruit'),
            self::NUTS => __('Nuts'),
            self::SEEDS => __('Seeds'),
        };
    }

    public function color(): array
    {
        return match($this) {
            self::BASE => ['bg' => 'sky-100', 'text' => 'sky-800'],
            self::SUGAR => ['bg' => 'pink-100', 'text' => 'pink-800'],
            self::ADDON => ['bg' => 'orange-100', 'text' => 'orange-800'],
            self::PRIMARY => ['bg' => 'amber-100', 'text' => 'amber-800'],
            self::SEED => ['bg' => 'emerald-100', 'text' => 'emerald-800'],
            self::LEGUME => ['bg' => 'violet-100', 'text' => 'violet-800'],
            self::NUT => ['bg' => 'rose-100', 'text' => 'rose-800'],
            self::FRUIT => ['bg' => 'blue-100', 'text' => 'blue-800'],
            self::PROTEIN => ['bg' => 'orange-100', 'text' => 'orange-800'],
            self::VEGETABLE => ['bg' => 'purple-100', 'text' => 'purple-800'],
            self::CONDIMENT => ['bg' => 'gray-100', 'text' => 'gray-800'],
            self::GRAIN => ['bg' => 'teal-100', 'text' => 'teal-800'],
            self::BREAD => ['bg' => 'cyan-100', 'text' => 'cyan-800'],
            self::OIL => ['bg' => 'fuchsia-100', 'text' => 'fuchsia-800'],
            self::SPICE => ['bg' => 'lime-100', 'text' => 'lime-800'],
            self::HERB => ['bg' => 'yellow-100', 'text' => 'yellow-800'],
            self::SYRUP => ['bg' => 'red-100', 'text' => 'red-800'],
            self::YOGURT => ['bg' => 'purple-100', 'text' => 'purple-800'],
            self::TOPPING => ['bg' => 'orange-100', 'text' => 'orange-800'],
            self::DRIED_FRUIT => ['bg' => 'brown-100', 'text' => 'brown-800'],
            self::NUTS => ['bg' => 'rose-100', 'text' => 'rose-800'],
            self::SEEDS => ['bg' => 'emerald-100', 'text' => 'emerald-800'],
        };
    }

    public function description(): string
    {
        return match($this) {
            self::BASE => __('Base ingredients are the main components of a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SUGAR => __('Sugar ingredients are used to sweeten a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::ADDON => __('Add-on ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::PRIMARY => __('Primary ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SEED => __('Seed ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::LEGUME => __('Legume ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::NUT => __('Nut ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::FRUIT => __('Fruit ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::PROTEIN => __('Protein ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::VEGETABLE => __('Vegetable ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::CONDIMENT => __('Condiment ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::GRAIN => __('Grain ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::BREAD => __('Bread ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::OIL => __('Oil ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SPICE => __('Spice ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::HERB => __('Herb ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SYRUP => __('Syrup ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::YOGURT => __('Yogurt ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::TOPPING => __('Topping ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::DRIED_FRUIT => __('Dried Fruit ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::NUTS => __('Nuts ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
            self::SEEDS => __('Seeds ingredients are used to add flavor to a recipe. They are the foundation of the dish and provide the structure and flavor.'),
        };
    }
}
