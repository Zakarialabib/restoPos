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
            ['name' => 'Salade', 'description' => 'Various salade and preparations'],
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
            [
                'name' => 'Moroccan Sandwiches - ساندويتشات مغربية',
                'description' => 'Traditional Moroccan sandwiches with fresh ingredients',
            ],
            [
                'name' => 'Salades Marocaines - سلطات مغربية',
                'description' => 'Fresh Moroccan-style salads',
            ],
            [
                'name' => 'Smoothies & Jus - عصائر و سموذي',
                'description' => 'Fresh fruit smoothies and juices',
            ],
            [
                'name' => 'Bols Santé - أطباق صحية',
                'description' => 'Healthy bowls with Moroccan flavors',
            ],
            [
                'name' => 'Fruits Secs - فواكه جافة',
                'description' => 'Dried fruits and nuts',
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
