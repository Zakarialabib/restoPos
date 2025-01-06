<?php

declare(strict_types=1);

namespace App\Enums;

enum RecipeType: string
{
    case Main = 'main';
    case Side = 'side';
    case Beverage = 'beverage';
    case Dessert = 'dessert';
    case Juice = 'juice';
    case Smoothie = 'smoothie';
    case Cocktail = 'cocktail';
    case Tea = 'tea';
    case Coffee = 'coffee';
    case Custom = 'custom';

    public function label(): string
    {
        return match($this) {
            self::Juice => 'Juice',
            self::Smoothie => 'Smoothie',
            self::Cocktail => 'Cocktail',
            self::Tea => 'Tea',
            self::Coffee => 'Coffee',
            self::Custom => 'Custom',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Juice => '🥤',
            self::Smoothie => '🥛',
            self::Cocktail => '🍸',
            self::Tea => '🫖',
            self::Coffee => '☕',
            self::Custom => '🔧',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Juice => 'Fresh pressed juice drinks',
            self::Smoothie => 'Blended fruit and vegetable drinks',
            self::Cocktail => 'Mixed alcoholic and non-alcoholic beverages',
            self::Tea => 'Hot and cold tea beverages',
            self::Coffee => 'Coffee-based drinks',
            self::Custom => 'Custom drink recipes',
        };
    }

    public function defaultSize(): string
    {
        return match($this) {
            self::Juice, self::Smoothie => 'regular',
            self::Cocktail => 'small',
            self::Tea, self::Coffee => 'regular',
            self::Custom => 'regular',
        };
    }

    public function availableSizes(): array
    {
        return match($this) {
            self::Juice, self::Smoothie => [
                'small' => ['name' => 'Small', 'capacity' => '300ml'],
                'regular' => ['name' => 'Regular', 'capacity' => '500ml'],
                'large' => ['name' => 'Large', 'capacity' => '700ml'],
            ],
            self::Cocktail => [
                'small' => ['name' => 'Regular', 'capacity' => '200ml'],
                'double' => ['name' => 'Double', 'capacity' => '400ml'],
            ],
            self::Tea, self::Coffee => [
                'small' => ['name' => 'Small', 'capacity' => '250ml'],
                'regular' => ['name' => 'Regular', 'capacity' => '350ml'],
                'large' => ['name' => 'Large', 'capacity' => '450ml'],
            ],
            self::Custom => [
                'small' => ['name' => 'Small', 'capacity' => '300ml'],
                'regular' => ['name' => 'Regular', 'capacity' => '500ml'],
                'large' => ['name' => 'Large', 'capacity' => '700ml'],
            ],
        };
    }

    public function allowedIngredientTypes(): array
    {
        return match($this) {
            self::Juice => ['fruit', 'vegetable'],
            self::Smoothie => ['fruit', 'vegetable', 'dairy', 'supplement'],
            self::Cocktail => ['fruit', 'alcohol', 'mixer', 'garnish'],
            self::Tea => ['tea', 'herb', 'sweetener'],
            self::Coffee => ['coffee', 'dairy', 'syrup', 'topping'],
            self::Custom => ['fruit', 'vegetable', 'dairy', 'supplement', 'sweetener', 'topping'],
        };
    }

    public function baseIngredientType(): string
    {
        return match($this) {
            self::Juice => 'fruit',
            self::Smoothie => 'fruit',
            self::Cocktail => 'alcohol',
            self::Tea => 'tea',
            self::Coffee => 'coffee',
            self::Custom => 'fruit',
        };
    }
}
