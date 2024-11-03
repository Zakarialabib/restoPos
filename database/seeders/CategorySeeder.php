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
            ['name' => 'Fruits', 'description' => 'Fresh fruits for juices and smoothies'],
            ['name' => 'Coffee', 'description' => 'Various coffee drinks and preparations'],
            ['name' => 'Dried Fruits', 'description' => 'Selection of dried and preserved fruits'],
            ['name' => 'Base', 'description' => 'Base ingredients'],
            ['name' => 'Topping', 'description' => 'Topping ingredients'],
            ['name' => 'Liquid', 'description' => 'Liquid ingredients'],
            ['name' => 'Sweetener', 'description' => 'Sweetener ingredients'],
            ['name' => 'Ice', 'description' => 'Ice ingredients'],
            ['name' => 'Nuts', 'description' => 'Nuts ingredients'],
            ['name' => 'Seeds', 'description' => 'Seeds ingredients'],
            ['name' => 'Dairy', 'description' => 'Dairy ingredients'],
            ['name' => 'Herbs', 'description' => 'Herbs ingredients'],
            ['name' => 'Spices', 'description' => 'Spices ingredients'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
