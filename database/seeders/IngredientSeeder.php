<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Unit;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // Base Ingredients
            [
                'name' => 'Ble - قمح',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Blilola - بليلولة',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 800,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],

            [
                'name' => 'Oreo - اوريو',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Chocolate - شوكولاطة',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            // Fruits
            [
                'name' => 'Orange - برتقال',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Dragon Fruit - فاكهة التنين',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 300,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Pomme - تفاح',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Bannane - الموز',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Ananas - اناناس',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Peach - الخوخ',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 750,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Avocate - الافوكادو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 600,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Pear - الاجاص',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 900,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Papaya - البابايا',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Citron - الحامض',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Mangue - المانجو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Lait - حليب',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => Unit::Mililitre->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            [
                'name' => 'Glace - الماء مثلج',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => Unit::Mililitre->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],

            [
                'name' => 'Sucre - سكر',
                'category_id' => Category::where('name', 'Sweetener')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 5000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ],
            // Add toppings/addons
            [
                'name' => 'Fruits Secs - فواكه مجففة',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => Unit::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => [
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]
            ]
            // noix de coco 
            // miel 
            // poudre de cacao 
            // poudre de vanille 
            // 
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
