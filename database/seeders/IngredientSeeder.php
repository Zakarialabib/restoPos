<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $ingredients = [
            [
                'name' => 'Organic Mango',
                'category_id' => $categories['Seasonal Fruits']->id,
                'is_seasonal' => true,
                'season_start' => '03-01',
                'season_end' => '05-31',
                'base_cost' => 8.00,
                'markup_percentage' => 40,
                'unit' => 'kg',
                'type' => 'fruit'
            ],
            [
                'name' => 'Orange',
                'category_id' => $categories['Seasonal Fruits']->id,
                'is_seasonal' => false,
                'base_cost' => 5.00,
                'markup_percentage' => 30,
                'unit' => 'kg',
                'type' => 'fruit'
            ],
            [
                'name' => 'Apple',
                'category_id' => $categories['Seasonal Fruits']->id,
                'is_seasonal' => false,
                'base_cost' => 4.00,
                'markup_percentage' => 35,
                'unit' => 'kg',
                'type' => 'fruit'
            ],
            [
                'name' => 'Pineapple',
                'category_id' => $categories['Seasonal Fruits']->id,
                'is_seasonal' => true,
                'season_start' => '06-01',
                'season_end' => '08-31',
                'base_cost' => 6.00,
                'markup_percentage' => 40,
                'unit' => 'kg',
                'type' => 'fruit'
            ],
            [
                'name' => 'Water',
                'category_id' => $categories['Base Ingredients']->id,
                'base_cost' => 1.00,
                'markup_percentage' => 20,
                'unit' => 'liter',
                'type' => 'base'
            ],
            [
                'name' => 'Sugar',
                'category_id' => $categories['Base Ingredients']->id,
                'base_cost' => 2.00,
                'markup_percentage' => 25,
                'unit' => 'kg',
                'type' => 'condiment'
            ],
            [
                'name' => 'Mint',
                'category_id' => $categories['Toppings']->id,
                'base_cost' => 3.00,
                'markup_percentage' => 50,
                'unit' => 'bunch',
                'type' => 'condiment'
            ]
        ];

        foreach ($ingredients as $ingredientData) {
            $ingredientData['slug'] = Str::slug($ingredientData['name']);
            Ingredient::firstOrCreate(['name' => $ingredientData['name']], $ingredientData);
        }
    }
}
