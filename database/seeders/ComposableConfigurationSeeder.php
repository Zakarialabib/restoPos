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
                    'sizes' => ['small', 'medium', 'large'],
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
                    'sizes' => ['small', 'medium', 'large'],
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
                    'sizes' => ['small', 'medium', 'large'],
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
    }
}
