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
            [
                'name' => 'Fruits',
                'description' => 'Fresh fruits for juices and smoothies',
                'status' => 1
            ],
            [
                'name' => 'Juices',
                'description' => 'Fresh juices',
                'status' => 1
            ],
            [
                'name' => 'Salade',
                'description' => 'Various salade and preparations',
                'status' => 1
            ],
            [
                'name' => 'Dried Fruits',
                'description' => 'Selection of dried and preserved fruits',
                'status' => 1
            ],
            [
                'name' => 'Base',
                'description' => 'Base ingredients',
                'status' => 1
            ],
            [
                'name' => 'Topping',
                'description' => 'Topping ingredients',
                'status' => 1
            ],
            [
                'name' => 'Liquid',
                'description' => 'Liquid ingredients',
                'status' => 1
            ],
            [
                'name' => 'Sweetener',
                'description' => 'Sweetener ingredients',
                'status' => 1
            ],
            [
                'name' => 'Ice',
                'description' => 'Ice ingredients',
                'status' => 1
            ],
            [
                'name' => 'Nuts',
                'description' => 'Nuts ingredients',
                'status' => 1
            ],
            [
                'name' => 'Seeds',
                'description' => 'Seeds ingredients',
                'status' => 1
            ],
            [
                'name' => 'Dairy',
                'description' => 'Dairy ingredients',
                'status' => 1
            ],
            [
                'name' => 'Herbs',
                'description' => 'Herbs ingredients',
                'status' => 1
            ],
            [
                'name' => 'Spices',
                'description' => 'Spices ingredients',
                'status' => 1
            ],
            [
                'name' => 'Moroccan Sandwiches - ساندويتشات مغربية',
                'description' => 'Traditional Moroccan sandwiches with fresh ingredients',
                'status' => 1

            ],
            [
                'name' => 'Salades Marocaines - سلطات مغربية',
                'description' => 'Fresh Moroccan-style salads',
                'status' => 1

            ],
            [
                'name' => 'Smoothies & Jus - عصائر و سموذي',
                'description' => 'Fresh fruit smoothies and juices',
                'status' => 1

            ],
            [
                'name' => 'Bols Santé - أطباق صحية',
                'description' => 'Healthy bowls with Moroccan flavors',
                'status' => 1
            ],
            [
                'name' => 'Fruits Secs - فواكه جافة',
                'description' => 'Dried fruits and nuts',
                'status' => 1
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
