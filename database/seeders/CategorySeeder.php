<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fresh Juices', 'slug' => 'juices', 'type' => 'product', 'is_composable' => true],
            ['name' => 'Fruit Bowls', 'slug' => 'fruit-bowls', 'type' => 'product', 'is_composable' => true],
            ['name' => 'Smoothies', 'slug' => 'smoothies', 'type' => 'product', 'is_composable' => true],
            ['name' => 'Seasonal Fruits', 'slug' => 'seasonal-fruits', 'type' => 'ingredient', 'is_composable' => false],
            ['name' => 'Base Ingredients', 'slug' => 'base-ingredients', 'type' => 'ingredient', 'is_composable' => false],
            ['name' => 'Toppings', 'slug' => 'toppings', 'type' => 'ingredient', 'is_composable' => false]
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
