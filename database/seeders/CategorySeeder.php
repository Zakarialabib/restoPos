<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Main product categories from previous implementation
            [
                'name' => 'Fruits',
                'description' => 'Fresh fruits for juices and smoothies',
                'status' => true,
                'is_composable' => true,
                'type' => CategoryType::FRUIT->value,
            ],
            [
                'name' => 'Base',
                'description' => 'Base ingredients for juices and smoothies',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::BASE->value,
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Fresh vegetables for salads and soups',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Breads',
                'description' => 'Various types of bread for sandwiches',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Proteins',
                'description' => 'Meats, fish, and plant-based proteins',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Dairy',
                'description' => 'Dairy products and alternatives',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Condiments',
                'description' => 'Sauces, dressings, and seasonings',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Soup Bases',
                'description' => 'Broths and soup bases',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Toppings',
                'description' => 'Additional toppings and garnishes',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Nuts',
                'description' => 'Various nuts and seeds for toppings',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Herbs',
                'description' => 'Fresh and dried herbs and spices',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Spices',
                'description' => 'Spices for cooking and seasoning',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Oils',
                'description' => 'Oils for cooking and seasoning',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            [
                'name' => 'Grains',
                'description' => 'Grains for cooking and seasoning',
                'status' => true,
                'is_composable' => false,
                'type' => CategoryType::INGREDIENT->value,
            ],
            // Composable product categories
            [
                'name' => 'Salad',
                'description' => 'Fresh and customizable salads',
                'status' => true,
                'is_composable' => true,
                'type' => CategoryType::COMPOSABLE->value,
            ],
            [
                'name' => 'Sandwiches',
                'description' => 'Custom sandwiches and wraps',
                'status' => true,
                'is_composable' => true,
                'type' => CategoryType::COMPOSABLE->value,
            ],
            [
                'name' => 'Soups',
                'description' => 'Hot and cold soups',
                'status' => true,
                'is_composable' => true,
                'type' => CategoryType::COMPOSABLE->value,
            ],
            [
                'name' => 'Chocolate',
                'description' => 'Chocolate and chocolate-related products',
                'status' => true,
                'is_composable' => true,
                'type' => CategoryType::COMPOSABLE->value,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
