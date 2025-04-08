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
        // Coffee Configurations
        $coffeeCategory = Category::where('name', 'Coffee')->first();
        if ($coffeeCategory) {
            ComposableConfiguration::create([
                'category_id' => $coffeeCategory->id,
                'name' => 'Coffee Customization',
                'description' => 'Customize your coffee with various options',
                'has_base' => true,
                'has_sugar' => true,
                'has_size' => true,
                'has_addons' => true,
                'min_ingredients' => 1,
                'max_ingredients' => 5,
                'sizes' => ['small', 'medium', 'large', 'x-large'],
                'base_types' => ['espresso', 'americano', 'latte', 'cappuccino', 'mocha'],
                'sugar_types' => ['none', 'regular', 'brown', 'honey', 'stevia'],
                'addon_types' => ['milk', 'cream', 'whipped_cream', 'chocolate_syrup', 'caramel_syrup'],
                'icons' => [
                    'base' => 'coffee',
                    'sugar' => 'sugar',
                    'size' => 'size',
                    'addons' => 'addons'
                ],
                'is_active' => true,
            ]);
        }

        // Tea Configurations
        $teaCategory = Category::where('name', 'Tea')->first();
        if ($teaCategory) {
            ComposableConfiguration::create([
                'category_id' => $teaCategory->id,
                'name' => 'Tea Customization',
                'description' => 'Customize your tea with various options',
                'has_base' => true,
                'has_sugar' => true,
                'has_size' => true,
                'has_addons' => true,
                'min_ingredients' => 1,
                'max_ingredients' => 4,
                'sizes' => ['small', 'medium', 'large'],
                'base_types' => ['black', 'green', 'herbal', 'oolong', 'white'],
                'sugar_types' => ['none', 'regular', 'honey', 'stevia'],
                'addon_types' => ['lemon', 'milk', 'honey', 'mint'],
                'icons' => [
                    'base' => 'tea',
                    'sugar' => 'sugar',
                    'size' => 'size',
                    'addons' => 'addons'
                ],
                'is_active' => true,
            ]);
        }

        // Smoothie Configurations
        $smoothieCategory = Category::where('name', 'Smoothies')->first();
        if ($smoothieCategory) {
            ComposableConfiguration::create([
                'category_id' => $smoothieCategory->id,
                'name' => 'Smoothie Customization',
                'description' => 'Customize your smoothie with various options',
                'has_base' => true,
                'has_sugar' => true,
                'has_size' => true,
                'has_addons' => true,
                'min_ingredients' => 2,
                'max_ingredients' => 6,
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
                'is_active' => true,
            ]);
        }
    }
}
