<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UnitType;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Price;
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
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 20.00,
                ]
            ],
            [
                'name' => 'Blilola - بليلولة',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 800,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 20.00,
                ]
            ],

            [
                'name' => 'Oreo - اوريو',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Units->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 3.5,
                    'price' => 5,
                ]
            ],
            [
                'name' => 'Chocolate - شوكولاطة',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Litre->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            // Fruits
            [
                'name' => 'Orange - برتقال',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 5.00,
                    'price' => 7.00,
                ]
            ],
            [
                'name' => 'Dragon Fruit - فاكهة التنين',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 300,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            [
                'name' => 'Pomme - تفاح',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'name' => 'Bannane - الموز',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 1500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'name' => 'Ananas - اناناس',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 18.00,
                    'price' => 25.00,
                ]
            ],
            [
                'name' => 'Peach - الخوخ',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 750,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            [
                'name' => 'Avocate - الافوكادو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 600,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 18.00,
                    'price' => 25.00,
                ]
            ],
            [
                'name' => 'Pear - الاجاص',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 900,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 20.00,
                    'price' => 30.00,
                ]
            ],
            [
                'name' => 'Papaya - البابايا',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 20.00,
                    'price' => 30.00,
                ]
            ],
            [
                'name' => 'Citron - الحامض',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 6.00,
                    'price' => 10.00,
                ]
            ],
            [
                'name' => 'Mangue - المانجو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            [
                'name' => 'Lait - حليب',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => UnitType::Litre->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ]),
                'price' => [
                    'cost' => 8.00,
                    'price' => 10.00,
                ]
            ],
            [
                'name' => 'Glace - الماء مثلج',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => UnitType::Mililitre->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            [
                'name' => 'Sucre - سكر',
                'category_id' => Category::where('name', 'Sweetener')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 5000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            [
                'name' => 'Fruits Secs - فواكه مجففة',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            [
                'name' => 'Lait de Coco - حليب جوز الهند',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => UnitType::Mililitre->value,
                'conversion_rate' => 1,
                'stock' => 2000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 230,
                    'protein' => 2.3,
                    'carbs' => 5.5,
                    'fat' => 24
                ])
            ],
            [
                'name' => 'Miel - عسل',
                'category_id' => Category::where('name', 'Sweetener')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 304,
                    'protein' => 0.3,
                    'carbs' => 82.4,
                    'fat' => 0
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 14.00,
                ]
            ],
            [
                'name' => 'Yogourt - زبادي',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Litre->value,
                'conversion_rate' => 1,
                'stock' => 2000,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 59,
                    'protein' => 3.5,
                    'carbs' => 4.7,
                    'fat' => 3.3
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 14.00,
                ]
            ],
            [
                'name' => 'Poudre de Cacao - مسحوق الكاكاو',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 228,
                    'protein' => 19.6,
                    'carbs' => 57.9,
                    'fat' => 13.7
                ])
            ],

            [
                'name' => 'Avoine - شوفان',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Kilo->value,
                'conversion_rate' => 1,
                'stock' => 2000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 389,
                    'protein' => 16.9,
                    'carbs' => 66.3,
                    'fat' => 6.9
                ]),
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],

        ];

        foreach ($ingredients as $ingredientData) {
            // Check if 'price' key exists before accessing it
            $priceData = $ingredientData['price'] ?? null;
            if ($priceData) {
                unset($ingredientData['price']);

                $ingredient = Ingredient::create($ingredientData);

                Price::create([
                    'priceable_type' => Ingredient::class,
                    'priceable_id' => $ingredient->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'date' => now(),
                ]);
            }
        }
    }
}
