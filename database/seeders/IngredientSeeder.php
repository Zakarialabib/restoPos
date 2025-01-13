<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UnitType;
use App\Enums\IngredientType;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Price;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $ingredients = [
            [
                'sku' => 'Ing-01',
                'name' => 'Orange - برتقال',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 5.00,
                    'price' => 7.00,
                ]
            ],
            [
                'sku' => 'Ing-02',
                'name' => 'Citron - ليمون',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 300,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 4.00,
                    'price' => 6.00,
                ]
            ],
            [
                'sku' => 'Ing-03',
                'name' => 'Ice - ثلج',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 0.50,
                    'price' => 1.00,
                ]
            ],
            [
                'sku' => 'Ing-04',
                'name' => 'Apple - تفاح',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 16.00,
                ]
            ],
            [
                'sku' => 'Ing-05',
                'name' => 'Carrot - جزر',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 800,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 6.00,
                    'price' => 10.00,
                ]
            ],
            [
                'sku' => 'Ing-06',
                'name' => 'Ginger - زنجبيل',
                'category_id' => $categories['Spices']->id,
                'type' => IngredientType::SPICE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 200,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 15.00,
                    'price' => 20.00,
                ]
            ],
            [
                'sku' => 'Ing-07',
                'name' => 'Mint - نعناع',
                'category_id' => $categories['Herbs']->id,
                'type' => IngredientType::HERB,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 300,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            [
                'sku' => 'Ing-08',
                'name' => 'Pineapple - أناناس',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 18.00,
                    'price' => 25.00,
                ]
            ],
            [
                'sku' => 'Ing-09',
                'name' => 'Strawberry - فراولة',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 20.00,
                    'price' => 30.00,
                ]
            ],
            [
                'sku' => 'Ing-10',
                'name' => 'Watermelon - بطيخ',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 600,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            [
                'sku' => 'Ing-11',
                'name' => 'Ble - قمح',
                'category_id' => $categories['Base']->id,
                'type' => IngredientType::GRAIN,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 20.00,
                ]
            ],
            [
                'sku' => 'Ing-12',
                'name' => 'Blilola - بليلولة',
                'category_id' => $categories['Base']->id,
                'type' => IngredientType::GRAIN,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 800,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 20.00,
                ]
            ],
            [
                'sku' => 'Ing-13',
                'name' => 'Oreo - اوريو',
                'category_id' => $categories['Toppings']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Unit->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 3.5,
                    'price' => 5,
                ]
            ],
            [
                'sku' => 'Ing-14',
                'name' => 'Chocolate - شوكولاطة',
                'category_id' => $categories['Toppings']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Liter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            // Fruits
            [
                'sku' => 'Ing-15',
                'name' => 'Pamplemousse - بومبليموس',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 5.00,
                    'price' => 7.00,
                ]
            ],
            [
                'sku' => 'Ing-16',
                'name' => 'Dragon Fruit - فاكهة التنين',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 300,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            [
                'sku' => 'Ing-17',
                'name' => 'Pomme - تفاح',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 16.00,
                ]
            ],
            [
                'sku' => 'Ing-18',
                'name' => 'Bannane - الموز',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'sku' => 'Ing-19',
                'name' => 'Ananas - اناناس',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 18.00,
                    'price' => 25.00,
                ]
            ],
            [
                'sku' => 'Ing-20',
                'name' => 'Peach - الخوخ',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 750,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            [
                'sku' => 'Ing-21',
                'name' => 'Avocate - الافوكادو',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 600,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 18.00,
                    'price' => 25.00,
                ]
            ],
            [
                'sku' => 'Ing-22',
                'name' => 'Pear - الاجاص',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 900,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 20.00,
                    'price' => 30.00,
                ]
            ],
            [
                'sku' => 'Ing-23',
                'name' => 'Papaya - البابايا',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 20.00,
                    'price' => 30.00,
                ]
            ],
            [
                'sku' => 'Ing-24',
                'name' => 'Citron - الحامض',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 6.00,
                    'price' => 10.00,
                ]
            ],
            [
                'sku' => 'Ing-25',
                'name' => 'Mangue - المانجو',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 30.00,
                    'price' => 40.00,
                ]
            ],
            [
                'sku' => 'Ing-26',
                'name' => 'Lait - حليب',
                'category_id' => $categories['Dairy']->id,
                'type' => IngredientType::BASE,
                'unit' => UnitType::Liter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 10.00,
                ]
            ],
            [
                'sku' => 'Ing-27',
                'name' => 'Glace - الماء مثلج',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::BASE,
                'unit' => UnitType::Milliliter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 0.50,
                    'price' => 1.00,
                ]
            ],
            [
                'sku' => 'Ing-28',
                'name' => 'Sucre - سكر',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::SUGAR,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 5000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 2.00,
                    'price' => 3.00,
                ]
            ],
            [
                'sku' => 'Ing-29',
                'name' => 'Fruits Secs - فواكه مجففة',
                'category_id' => $categories['Toppings']->id,
                'type' => IngredientType::NUT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 15.00,
                    'price' => 20.00,
                ]
            ],
            [
                'sku' => 'Ing-30',
                'name' => 'Lait de Coco - حليب جوز الهند',
                'category_id' => $categories['Dairy']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Milliliter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 12.00,
                    'price' => 16.00,
                ]
            ],
            [
                'sku' => 'Ing-31',
                'name' => 'Miel - عسل',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::SUGAR,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 14.00,
                ]
            ],
            // Add-ons
            [
                'sku' => 'Ing-32',
                'name' => 'Yogourt - زبادي',
                'category_id' => $categories['Dairy']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Liter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 14.00,
                ]
            ],
            [
                'sku' => 'Ing-33',
                'name' => 'Poudre de Cacao - مسحوق الكاكاو',
                'category_id' => $categories['Toppings']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'sku' => 'Ing-34',
                'name' => 'Avoine - شوفان',
                'category_id' => $categories['Base']->id,
                'type' => IngredientType::GRAIN,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            // Spices
            [
                'sku' => 'Ing-35',
                'name' => 'Ras El Hanout - رأس الحانوت',
                'category_id' => $categories['Spices']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 0.10,
                    'price' => 0.25,
                ],
            ],
            [
                'sku' => 'Ing-36',
                'name' => 'Cumin - الكمون',
                'category_id' => $categories['Spices']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 0.12,
                    'price' => 0.30,
                ],
            ],
            [
                'sku' => 'Ing-37',
                'name' => 'Paprika - الفلفل الأحمر',
                'category_id' => $categories['Spices']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1200,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 0.10,
                    'price' => 0.20,
                ],
            ],
            // Herbs
            [
                'sku' => 'Ing-38',
                'name' => 'Mint - النعناع',
                'category_id' => $categories['Herbs']->id,
                'type' => IngredientType::CONDIMENT,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 800,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => false,
                'price' => [
                    'cost' => 0.05,
                    'price' => 0.15,
                ],
            ],
            // Oils
            [
                'sku' => 'Ing-39',
                'name' => 'Olive Oil - زيت الزيتون',
                'category_id' => $categories['Oils']->id,
                'type' => IngredientType::OIL,
                'unit' => UnitType::Liter->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => false,
                'price' => [
                    'cost' => 50.00,
                    'price' => 70.00,
                ],
            ],
            [
                'sku' => 'Ing-40',
                'name' => 'Thon - تونة',
                'category_id' => $categories['Proteins']->id,
                'type' => IngredientType::PROTEIN, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 40.00,
                    'price' => 60.00,
                ]
            ],
            // Vegetables
            [
                'sku' => 'Ing-41',
                'name' => 'Chickpeas - الحمص',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::SEED,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 600,
                'entry_date' => '2025-01-31',
                'expiry_date' => '2025-01-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 4.00,
                    'price' => 6.00,
                ],
            ],
            // Nuts
            [
                'sku' => 'Ing-42',
                'name' => 'Almonds - اللوز',
                'category_id' => $categories['Nuts']->id,
                'type' => IngredientType::NUT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 300,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 80.00,
                    'price' => 100.00,
                ],
            ],
            // Fruits
            [
                'sku' => 'Ing-43',
                'name' => 'Dates - التمر',
                'category_id' => $categories['Fruits']->id,
                'type' => IngredientType::FRUIT,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => false,
                'price' => [
                    'cost' => 15.00,
                    'price' => 25.00,
                ],
            ],

            [
                'sku' => 'Ing-44',
                'name' => 'Poulet Grillé - دجاج مشوي',
                'category_id' => $categories['Proteins']->id,
                'type' => IngredientType::PROTEIN, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 35.00,
                    'price' => 50.00,
                ]
            ],
            [
                'sku' => 'Ing-45', // Fixed SKU
                'name' => 'Crevettes - قمرون',
                'category_id' => $categories['Proteins']->id,
                'type' => IngredientType::PROTEIN, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 300,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 60.00,
                    'price' => 90.00,
                ]
            ],
            [
                'sku' => 'Ing-46', // Fixed SKU
                'name' => 'Viande Hachée - لحم مفروم',
                'category_id' => $categories['Proteins']->id,
                'type' => IngredientType::PROTEIN, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 45.00,
                    'price' => 65.00,
                ]
            ],
            // Vegetables for Sandwiches
            [
                'sku' => 'Ing-47',
                'name' => 'Tomate - طماطم',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 800,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'sku' => 'Ing-48',
                'name' => 'Oignon - بصل',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 600,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 6.00,
                    'price' => 9.00,
                ]
            ],
            [
                'sku' => 'Ing-49',
                'name' => 'Poivron - فلفل',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE, // Added type
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 15.00,
                ]
            ],
            // Sauces and Condiments
            [
                'sku' => 'Ing-50',
                'name' => 'Harissa - هريسة',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::CONDIMENT, // Added type
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-02-28', // Fixed date
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 15.00,
                    'price' => 25.00,
                ]
            ],
            [
                'sku' => 'Ing-51',
                'name' => 'Mayonnaise - مايونيز',
                'category_id' => $categories['Condiments']->id,
                'type' => IngredientType::CONDIMENT, // Added type
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 2000,
                'entry_date' => '2025-02-31',
                'expiry_date' => '2025-12-31',
                'is_composable' => true,
                'price' => [
                    'cost' => 10.00,
                    'price' => 18.00,
                ]
            ],
            // Breads
            [
                'sku' => 'Ing-52',
                'name' => 'Batbout - بطبوط',
                'category_id' => $categories['Breads']->id,
                'type' => IngredientType::BREAD, // Added type
                'unit' => UnitType::Unit->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 1.00,
                    'price' => 2.00,
                ]
            ],
            [
                'sku' => 'Ing-53',
                'name' => 'Khobz - خبز',
                'category_id' => $categories['Breads']->id,
                'type' => IngredientType::BREAD, // Added type
                'unit' => UnitType::Unit->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 1.50,
                    'price' => 3.00,
                ]
            ],
            [
                'sku' => 'Ing-54',
                'name' => 'Msemen - مسمن',
                'category_id' => $categories['Breads']->id,
                'type' => IngredientType::BREAD, // Added type
                'unit' => UnitType::Unit->value,
                'conversion_rate' => 1,
                'stock_quantity' => 800,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 2.00,
                    'price' => 4.00,
                ]
            ],
            // Moroccan Salad Base Vegetables
            [
                'sku' => 'Ing-55',
                'name' => 'Aubergine Grillée - باذنجان مشوي',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 12.00,
                    'price' => 18.00,
                ]
            ],
            [
                'sku' => 'Ing-56',
                'name' => 'Poivrons Grillés - فلفل مشوي',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 400,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 15.00,
                    'price' => 22.00,
                ]
            ],
            [
                'sku' => 'Ing-57',
                'name' => 'Carottes Râpées - جزر مبشور',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 600,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            [
                'sku' => 'Ing-59',
                'name' => 'Concombre - خيار',
                'category_id' => $categories['Vegetables']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Kilogram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 500,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 8.00,
                    'price' => 12.00,
                ]
            ],
            // Moroccan Salad Herbs and Seasonings
            [
                'sku' => 'Ing-60',
                'name' => 'Persil - بقدونس',
                'category_id' => $categories['Herbs']->id,
                'type' => IngredientType::VEGETABLE,
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 5.00,
                    'price' => 8.00,
                ]
            ],
            [
                'sku' => 'Ing-61',
                'name' => 'Coriandre - قزبور',
                'category_id' => $categories['Herbs']->id,
                'type' => IngredientType::VEGETABLE, // Added type
                'unit' => UnitType::Gram->value,
                'conversion_rate' => 1,
                'stock_quantity' => 1000,
                'entry_date' => '2025-06-30',
                'expiry_date' => '2025-06-30',
                'is_composable' => true,
                'price' => [
                    'cost' => 5.00,
                    'price' => 8.00,
                ]
            ],
        ];

        foreach ($ingredients as $ingredientData) {
            $priceData = $ingredientData['price'] ?? null;
            if ($priceData) {
                unset($ingredientData['price']);

                $ingredient = Ingredient::create($ingredientData);

                Price::create([
                    'priceable_type' => Ingredient::class,
                    'priceable_id' => $ingredient->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'entry_date' => now(),
                ]);
            }
        }
    }
}
