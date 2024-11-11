<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UnitType;
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
                'name' => 'Blilola - بليلولة',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 800,
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
                'name' => 'Oreo - اوريو',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
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
                'name' => 'Chocolate - شوكولاطة',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            // Fruits
            [
                'name' => 'Orange - برتقال',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            [
                'name' => 'Dragon Fruit - فاكهة التنين',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 300,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 47,
                    'protein' => 0.9,
                    'carbs' => 12,
                    'fat' => 0.1
                ])
            ],
            [
                'name' => 'Pomme - تفاح',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
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
                'name' => 'Bannane - الموز',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1500,
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
                'name' => 'Ananas - اناناس',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
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
                'name' => 'Peach - الخوخ',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 750,
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
                'name' => 'Avocate - الافوكادو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 600,
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
                'name' => 'Pear - الاجاص',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 900,
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
                'name' => 'Papaya - البابايا',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 400,
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
                'name' => 'Citron - الحامض',
                'category_id' => Category::where('name', 'Liquid')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
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
                'name' => 'Mangue - المانجو',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
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
                'name' => 'Lait - حليب',
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
                ])
            ],
            [
                'name' => 'Yogourt - زبادي',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 2000,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 59,
                    'protein' => 3.5,
                    'carbs' => 4.7,
                    'fat' => 3.3
                ])
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
                'name' => 'Poudre de Vanille - مسحوق الفانيليا',
                'category_id' => Category::where('name', 'Topping')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 500,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 288,
                    'protein' => 0,
                    'carbs' => 72,
                    'fat' => 0
                ])
            ],
            [
                'name' => 'Avoine - شوفان',
                'category_id' => Category::where('name', 'Base')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 2000,
                'expiry_date' => '2024-12-31',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 389,
                    'protein' => 16.9,
                    'carbs' => 66.3,
                    'fat' => 6.9
                ])
            ],
            [
                'name' => 'Fraise - فراولة',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 32,
                    'protein' => 0.7,
                    'carbs' => 7.7,
                    'fat' => 0.3
                ])
            ],
            [
                'name' => 'Kiwi - كيوي',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 1000,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 61,
                    'protein' => 1.1,
                    'carbs' => 14.7,
                    'fat' => 0.5
                ])
            ],
            [
                'name' => 'Framboise - توت العليق',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 800,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 52,
                    'protein' => 1.2,
                    'carbs' => 11.9,
                    'fat' => 0.7
                ])
            ],
            [
                'name' => 'Bleuet - توت أزرق',
                'category_id' => Category::where('name', 'Fruits')->first()->id,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock' => 800,
                'expiry_date' => '2024-06-30',
                'is_composable' => true,
                'nutritional_info' => json_encode([
                    'calories' => 57,
                    'protein' => 0.7,
                    'carbs' => 14.5,
                    'fat' => 0.3
                ])
            ]
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
