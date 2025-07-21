<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PortionConfiguration;
use Illuminate\Database\Seeder;

class PortionConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        // Burger Configurations
        $burgerCategory = Category::where('name', 'Burgers')->first();
        if ($burgerCategory) {
            PortionConfiguration::create([
                'category_id' => $burgerCategory->id,
                'name' => 'Standard Burger Configuration',
                'type' => 'burger',
                'sizes' => ['single', 'double', 'triple'],
                'addons' => ['extra_cheese', 'extra_patty', 'bacon', 'avocado'],
                'sides' => ['fries', 'onion_rings', 'salad'],
                'upgrades' => ['premium_bun', 'premium_cheese', 'premium_sauce'],
                'is_active' => true,
            ]);
        }

        // Pizza Configurations
        $pizzaCategory = Category::where('name', 'Pizzas')->first();
        if ($pizzaCategory) {
            PortionConfiguration::create([
                'category_id' => $pizzaCategory->id,
                'name' => 'Standard Pizza Configuration',
                'type' => 'pizza',
                'sizes' => ['small', 'medium', 'large', 'family'],
                'addons' => ['extra_cheese', 'extra_toppings', 'stuffed_crust'],
                'sides' => ['garlic_bread', 'salad', 'wings'],
                'upgrades' => ['premium_toppings', 'stuffed_crust', 'extra_sauce'],
                'is_active' => true,
            ]);
        }

        // Drink Configurations
        $drinkCategory = Category::where('name', 'Drinks')->first();
        if ($drinkCategory) {
            PortionConfiguration::create([
                'category_id' => $drinkCategory->id,
                'name' => 'Standard Drink Configuration',
                'type' => 'drink',
                'sizes' => ['small', 'medium', 'large', 'x-large'],
                'addons' => ['ice', 'lemon', 'straw'],
                'sides' => [],
                'upgrades' => [],
                'is_active' => true,
            ]);
        }

        // Fries Configurations
        $friesCategory = Category::where('name', 'Fries')->first();
        if ($friesCategory) {
            PortionConfiguration::create([
                'category_id' => $friesCategory->id,
                'name' => 'Standard Fries Configuration',
                'type' => 'fries',
                'sizes' => ['small', 'medium', 'large', 'family'],
                'addons' => ['cheese', 'bacon', 'chili'],
                'sides' => [],
                'upgrades' => [],
                'is_active' => true,
            ]);
        }

        // Combo Configurations
        $comboCategory = Category::where('name', 'Combos')->first();
        if ($comboCategory) {
            PortionConfiguration::create([
                'category_id' => $comboCategory->id,
                'name' => 'Standard Combo Configuration',
                'type' => 'combo',
                'sizes' => ['regular', 'large', 'mega'],
                'addons' => [],
                'sides' => ['fries', 'drink', 'salad', 'dessert'],
                'upgrades' => ['premium_side', 'larger_drink', 'dessert_upgrade'],
                'is_active' => true,
            ]);
        }
    }
}
