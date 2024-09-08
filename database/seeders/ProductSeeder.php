<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

use function Symfony\Component\String\b;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $fruitCategory = Category::firstOrCreate(['name' => 'Fruits']);

        $fruits = [
            [
                'name' => 'Apple',
                'description' => 'Fresh, crisp apples perfect for snacking or baking.',
                'price' => 0.99,
                'image' => 'https://example.com/images/apple.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 100,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Banana',
                'description' => 'Ripe, yellow bananas rich in potassium.',
                'price' => 0.59,
                'image' => 'https://example.com/images/banana.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 150,
                'low_stock_threshold' => 30,
            ],
            [
                'name' => 'Orange',
                'description' => 'Juicy oranges packed with vitamin C.',
                'price' => 0.79,
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 80,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Pineapple',
                'description' => 'Sweet and tangy tropical pineapples.',
                'price' => 1.99,
                'image' => 'https://example.com/images/pineapple.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 50,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Peach',
                'description' => 'Juicy and fragrant peaches, perfect for summer.',
                'price' => 1.29,
                'image' => 'https://example.com/images/peach.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 75,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Avocado',
                'description' => 'Creamy and nutritious avocados, great for salads and toast.',
                'price' => 1.49,
                'image' => 'https://example.com/images/avocado.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 60,
                'low_stock_threshold' => 12,
            ],
            [
                'name' => 'Pear',
                'description' => 'Juicy and sweet pears with a delicate flavor.',
                'price' => 0.89,
                'image' => 'https://example.com/images/pear.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 90,
                'low_stock_threshold' => 18,
            ],
            [
                'name' => 'Dragon Fruit',
                'description' => 'Exotic dragon fruit with a unique appearance and mild, sweet taste.',
                'price' => 2.49,
                'image' => 'https://example.com/images/dragon_fruit.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 30,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Papaya',
                'description' => 'Tropical papaya with a sweet, musky flavor and soft texture.',
                'price' => 1.79,
                'image' => 'https://example.com/images/papaya.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 40,
                'low_stock_threshold' => 8,
            ],
        ];

        foreach ($fruits as $fruit) {
            Product::create(array_merge($fruit, ['category_id' => $fruitCategory->id]));
        }

        // dried fruits category 
        $driedFruitsCategory = Category::firstOrCreate(['name' => 'Dried Fruits']);

        $driedFruits = [
            [
                'name' => 'Dried Candied Orange',
                'description' => 'Sweet and tangy dried candied orange slices.',
                'price' => 2.99,
                'image' => 'https://example.com/images/dried_candied_orange.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 50,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Dried Apple',
                'description' => 'Crispy and flavorful dried apple slices.',
                'price' => 1.99,
                'image' => 'https://example.com/images/dried_apple.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 60,
                'low_stock_threshold' => 12,
            ],
            [
                'name' => 'Dried Pineapple',
                'description' => 'Sweet and chewy dried pineapple chunks.',
                'price' => 2.49,
                'image' => 'https://example.com/images/dried_pineapple.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 40,
                'low_stock_threshold' => 8,
            ],
            [
                'name' => 'Dried Dragon Fruit',
                'description' => 'Unique and flavorful dried dragon fruit slices.',
                'price' => 3.49,
                'image' => 'https://example.com/images/dried_dragon_fruit.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 30,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Dried Papaya',
                'description' => 'Soft and sweet dried papaya strips.',
                'price' => 2.29,
                'image' => 'https://example.com/images/dried_papaya.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 45,
                'low_stock_threshold' => 9,
            ],
            [
                'name' => 'Dried Kiwi',
                'description' => 'Tart and tangy dried kiwi slices.',
                'price' => 2.79,
                'image' => 'https://example.com/images/dried_kiwi.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 35,
                'low_stock_threshold' => 7,
            ],
            [
                'name' => 'Dried Lemon',
                'description' => 'Zesty and sour dried lemon slices.',
                'price' => 1.79,
                'image' => 'https://example.com/images/dried_lemon.jpg',
                'is_available' => true,
                'is_composable' => true,
                'stock' => 55,
                'low_stock_threshold' => 11,
            ],
        ];

        foreach ($driedFruits as $driedFruit) {
            Product::create(array_merge($driedFruit, ['category_id' => $driedFruitsCategory->id]));
        }

        // base juices category 
        $baseJuicesCategory = Category::firstOrCreate(['name' => 'Base Juices']);

        $baseJuices = [
            [
                'name' => 'Milk',
                'description' => 'Fresh milk from local farms.',
                'price' => 1.99,
                'image' => 'https://example.com/images/milk.jpg',
                'is_available' => true,
                'is_composable' => false,
                'stock' => 100,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh orange juice.',
                'price' => 1.99,
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'is_composable' => false,
                'stock' => 100,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Ice Cold Water',
                'description' => 'Ice cold water.',
                'price' => 1.99,
                'image' => 'https://example.com/images/water.jpg',
                'is_available' => true,
                'is_composable' => false,
                'stock' => 100,
                'low_stock_threshold' => 20,
            ],
        ];

        foreach ($baseJuices as $baseJuice) {
            Product::create(array_merge($baseJuice, ['category_id' => $baseJuicesCategory->id]));
        }
    }
}
