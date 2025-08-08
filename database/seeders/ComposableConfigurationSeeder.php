<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ComposableConfiguration;
use Illuminate\Database\Seeder;

class ComposableConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        // Fresh Juices Configurations
        $juicesCategory = Category::where('name', 'Fresh Juices')->first();
        if ($juicesCategory) {
            ComposableConfiguration::firstOrCreate(
                [
                    'category_id' => $juicesCategory->id,
                    'type' => 'juice',
                ],
                [
                    'category_id' => $juicesCategory->id,
                    'name' => 'Juice Customization',
                    'type' => 'juice',
                    'customization_options' => [
                        'has_base' => true,
                        'has_sugar' => true,
                        'has_size' => true,
                        'has_addons' => true,
                        'min_ingredients' => 1,
                        'max_ingredients' => 5,
                    ],
                    'sizes' => [
                        'small' => [
                            'name' => 'Small',
                            'capacity' => '250ml',
                            'price_multiplier' => 0.8,
                            'base_price' => 15.00,
                        ],
                        'medium' => [
                            'name' => 'Medium',
                            'capacity' => '400ml',
                            'price_multiplier' => 1.0,
                            'base_price' => 20.00,
                        ],
                        'large' => [
                            'name' => 'Large',
                            'capacity' => '600ml',
                            'price_multiplier' => 1.4,
                            'base_price' => 25.00,
                        ],
                    ],
                    'base_types' => ['orange', 'apple', 'pineapple', 'mixed'],
                    'sugar_types' => ['none', 'regular', 'low'],
                    'addon_types' => ['ice', 'mint', 'ginger'],
                    'icons' => [
                        'base' => 'juice',
                        'sugar' => 'sugar',
                        'size' => 'size',
                        'addons' => 'addons'
                    ],
                    'status' => true,
                ]
            );
        }

        // Fruit Bowls Configurations
        $fruitBowlsCategory = Category::where('name', 'Fruit Bowls')->first();
        if ($fruitBowlsCategory) {
            ComposableConfiguration::firstOrCreate(
                [
                    'category_id' => $fruitBowlsCategory->id,
                    'type' => 'fruit_bowl',
                ],
                [
                    'category_id' => $fruitBowlsCategory->id,
                    'name' => 'Fruit Bowl Customization',
                    'type' => 'fruit_bowl',
                    'customization_options' => [
                        'has_base' => false,
                        'has_sugar' => true,
                        'has_size' => true,
                        'has_addons' => true,
                        'min_ingredients' => 3,
                        'max_ingredients' => 7,
                    ],
                    'sizes' => [
                        'small' => [
                            'name' => 'Small',
                            'capacity' => '200g',
                            'price_multiplier' => 0.8,
                            'base_price' => 12.00,
                        ],
                        'medium' => [
                            'name' => 'Medium',
                            'capacity' => '300g',
                            'price_multiplier' => 1.0,
                            'base_price' => 15.00,
                        ],
                        'large' => [
                            'name' => 'Large',
                            'capacity' => '450g',
                            'price_multiplier' => 1.4,
                            'base_price' => 20.00,
                        ],
                    ],
                    'base_types' => [],
                    'sugar_types' => ['none', 'honey', 'yogurt'],
                    'addon_types' => ['nuts', 'seeds', 'dried_fruits'],
                    'icons' => [
                        'sugar' => 'sugar',
                        'size' => 'size',
                        'addons' => 'addons'
                    ],
                    'status' => true,
                ]
            );
        }

        // Smoothies Configurations
        $smoothieCategory = Category::where('name', 'Smoothies')->first();
        if ($smoothieCategory) {
            ComposableConfiguration::firstOrCreate(
                [
                    'category_id' => $smoothieCategory->id,
                    'type' => 'smoothie',
                ],
                [
                    'category_id' => $smoothieCategory->id,
                    'name' => 'Smoothie Customization',
                    'type' => 'smoothie',
                    'customization_options' => [
                        'has_base' => true,
                        'has_sugar' => true,
                        'has_size' => true,
                        'has_addons' => true,
                        'min_ingredients' => 2,
                        'max_ingredients' => 6,
                    ],
                    'sizes' => [
                        'small' => [
                            'name' => 'Small',
                            'capacity' => '300ml',
                            'price_multiplier' => 0.8,
                            'base_price' => 18.00,
                        ],
                        'medium' => [
                            'name' => 'Medium',
                            'capacity' => '450ml',
                            'price_multiplier' => 1.0,
                            'base_price' => 22.00,
                        ],
                        'large' => [
                            'name' => 'Large',
                            'capacity' => '650ml',
                            'price_multiplier' => 1.4,
                            'base_price' => 28.00,
                        ],
                    ],
                    'base_types' => ['fruit', 'vegetable', 'protein', 'yogurt'],
                    'sugar_types' => ['none', 'honey', 'agave', 'stevia'],
                    'addon_types' => ['protein_powder', 'chia_seeds', 'flax_seeds', 'coconut_flakes'],
                    'icons' => [
                        'base' => 'smoothie',
                        'sugar' => 'sugar',
                        'size' => 'size',
                        'addons' => 'addons'
                    ],
                    'status' => true,
                ]
            );
        }

        // Layered Juices Configuration
        $layeredJuiceCategory = Category::where('name', 'Layered Juices')->first();
        if ($layeredJuiceCategory) {
            ComposableConfiguration::firstOrCreate(
                [
                    'category_id' => $layeredJuiceCategory->id,
                    'type' => 'juice',
                ],
                [
                    'category_id' => $layeredJuiceCategory->id,
                    'name' => 'Layered Juice Customization',
                    'type' => 'juice',
                    'customization_options' => [
                        'has_base' => false,
                        'has_sugar' => false,
                        'has_size' => true,
                        'has_addons' => false,
                        'min_ingredients' => 2,
                        'max_ingredients' => 5,
                        'supports_layers' => true,
                    ],
                    'sizes' => [
                        'small' => [
                            'name' => 'Small',
                            'capacity' => '250ml',
                            'price_multiplier' => 0.8,
                            'base_price' => 18.00,
                        ],
                        'medium' => [
                            'name' => 'Medium',
                            'capacity' => '400ml',
                            'price_multiplier' => 1.0,
                            'base_price' => 24.00,
                        ],
                        'large' => [
                            'name' => 'Large',
                            'capacity' => '600ml',
                            'price_multiplier' => 1.4,
                            'base_price' => 32.00,
                        ],
                    ],
                    'base_types' => [],
                    'sugar_types' => [],
                    'addon_types' => [],
                    'icons' => [
                        'size' => 'size',
                        'layers' => 'layers'
                    ],
                    'status' => true,
                ]
            );
        }

        // Protein Bowls Configuration
        $proteinBowlCategory = Category::where('name', 'Protein Bowls')->first();
        if ($proteinBowlCategory) {
            ComposableConfiguration::firstOrCreate(
                [
                    'category_id' => $proteinBowlCategory->id,
                    'type' => 'protein_bowl',
                ],
                [
                    'category_id' => $proteinBowlCategory->id,
                    'name' => 'Protein Bowl Customization',
                    'type' => 'protein_bowl',
                    'customization_options' => [
                        'has_base' => true,
                        'has_sugar' => false,
                        'has_size' => true,
                        'has_addons' => true,
                        'min_ingredients' => 4,
                        'max_ingredients' => 8,
                    ],
                    'sizes' => [
                        'small' => [
                            'name' => 'Small',
                            'capacity' => '250g',
                            'price_multiplier' => 0.8,
                            'base_price' => 16.00,
                        ],
                        'medium' => [
                            'name' => 'Medium',
                            'capacity' => '350g',
                            'price_multiplier' => 1.0,
                            'base_price' => 20.00,
                        ],
                        'large' => [
                            'name' => 'Large',
                            'capacity' => '500g',
                            'price_multiplier' => 1.4,
                            'base_price' => 26.00,
                        ],
                    ],
                    'base_types' => ['quinoa', 'rice', 'oats'],
                    'sugar_types' => [],
                    'addon_types' => ['protein', 'vegetables', 'nuts', 'seeds'],
                    'icons' => [
                        'base' => 'protein',
                        'size' => 'size',
                        'addons' => 'addons'
                    ],
                    'status' => true,
                ]
            );
        }
    }
}
