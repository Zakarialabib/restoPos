<?php

declare(strict_types=1);

namespace App\Enums;

enum ItemType: string
{
    case INGREDIENT = 'ingredient';
    case CATEGORY = 'category';
    case BASE = 'base';
    case SUGAR = 'sugar';
    case ADDON = 'addon';
    case FRUIT = 'fruit';
    case PROTEIN = 'protein';
    case VEGETABLE = 'vegetable';
    case CONDIMENT = 'condiment';
    case OIL = 'oil';
    case SPICE = 'spice';
    case HERB = 'herb';
    case LEGUME = 'legume';
    case NUT = 'nut';

    public function label(): string
    {
        return match($this) {
            self::INGREDIENT => __('Ingredient'),
            self::CATEGORY => __('Category'),
            self::BASE => __('Base'),
            self::SUGAR => __('Sugar'),
            self::ADDON => __('Add-on'),
            self::FRUIT => __('Fruit'),
            self::PROTEIN => __('Protein'),
            self::VEGETABLE => __('Vegetable'),
            self::CONDIMENT => __('Condiment'),
            self::OIL => __('Oil'),
            self::SPICE => __('Spice'),
            self::HERB => __('Herb'),
            self::LEGUME => __('Legume'),
            self::NUT => __('Nut'),
        };
    }

    public function description(): string
    {
        return match($this) {
            self::INGREDIENT => __('Ingredients are the building blocks of recipes'),
            self::CATEGORY => __('Categories help organize items'),
            self::BASE => __('Base ingredients form the foundation of a recipe'),
            self::SUGAR => __('Sugar ingredients add sweetness'),
            self::ADDON => __('Add-on ingredients are optional extras'),
            self::FRUIT => __('Fruit ingredients add natural sweetness and flavor'),
            self::PROTEIN => __('Protein ingredients provide nutritional value'),
            self::VEGETABLE => __('Vegetable ingredients add nutrients and texture'),
            self::CONDIMENT => __('Condiment ingredients enhance flavor'),
            self::OIL => __('Oil ingredients are used for cooking and flavor'),
            self::SPICE => __('Spice ingredients add aromatic flavors'),
            self::HERB => __('Herb ingredients add fresh flavors'),
            self::LEGUME => __('Legume ingredients provide protein and fiber'),
            self::NUT => __('Nut ingredients add texture and protein'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::INGREDIENT => 'blue',
            self::CATEGORY => 'purple',
            self::BASE => 'gray',
            self::SUGAR => 'pink',
            self::ADDON => 'yellow',
            self::FRUIT => 'red',
            self::PROTEIN => 'orange',
            self::VEGETABLE => 'green',
            self::CONDIMENT => 'indigo',
            self::OIL => 'amber',
            self::SPICE => 'brown',
            self::HERB => 'emerald',
            self::LEGUME => 'teal',
            self::NUT => 'warmGray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::INGREDIENT => '🥘',
            self::CATEGORY => '📁',
            self::BASE => '🍚',
            self::SUGAR => '🍯',
            self::ADDON => '➕',
            self::FRUIT => '🍎',
            self::PROTEIN => '🥩',
            self::VEGETABLE => '🥬',
            self::CONDIMENT => '🧂',
            self::OIL => '🫗',
            self::SPICE => '🌶️',
            self::HERB => '🌿',
            self::LEGUME => '🫘',
            self::NUT => '🥜',
        };
    }
}
