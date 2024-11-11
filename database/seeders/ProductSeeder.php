<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Fruits Category
        $fruitsCategory = Category::firstOrCreate([
            'id' => 1,
            'slug' => 'fruits',
            'name' => 'Fruits'
        ]);

        $fruits = [
            [
                'name' => 'Jus de pomme - عصير التفاح',
                'description' => 'Fresh, crisp apples perfect for snacking or baking.',
                'price' => 0.99,
                'image' => 'https://example.com/images/apple.jpg',
                'is_available' => true,
                'recipe_id' => Recipe::where('name', 'Jus de pomme - عصير التفاح')->first()->id ?? null
            ],
            [
                'name' => 'Panache - باناش',
                'description' => 'Fresh fruit mix juice',
                'price' => 4.99,
                'image' => 'https://example.com/images/panache.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus de banane - عصير الموز',
                'description' => 'Ripe, yellow bananas rich in potassium.',
                'price' => 0.59,
                'image' => 'https://example.com/images/banana.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => "Jus d'orange - عصير البرتقال",
                'description' => 'Juicy oranges packed with vitamin C.',
                'price' => 0.79,
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus d\'ananas - عصير الاناناس',
                'description' => 'Sweet and tangy tropical pineapples.',
                'price' => 1.99,
                'image' => 'https://example.com/images/pineapple.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus de pêche - عصير الخوخ',
                'description' => 'Juicy and fragrant peaches, perfect for summer.',
                'price' => 1.29,
                'image' => 'https://example.com/images/peach.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus d\'avocat - عصير الافوكادو',
                'description' => 'Creamy and nutritious avocados, great for salads and toast.',
                'price' => 1.49,
                'image' => 'https://example.com/images/avocado.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus de poire - عصير الاجاص',
                'description' => 'Juicy and sweet pears with a delicate flavor.',
                'price' => 0.89,
                'image' => 'https://example.com/images/pear.jpg',
                'is_available' => true,
                'recipe_id' => Recipe::where('name', 'Jus de poire - عصير الاجاص')->first()->id ?? null
            ],
            [
                'name' => 'Jus de dragon fruit - عصير فاكهة التنين',
                'description' => 'Exotic dragon fruit with a unique appearance and mild, sweet taste.',
                'price' => 2.49,
                'image' => 'https://example.com/images/dragon_fruit.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus de papaye - عصير البابايا',
                'description' => 'Tropical papaya with a sweet, musky flavor and soft texture.',
                'price' => 1.79,
                'image' => 'https://example.com/images/papaya.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus de mangue - عصير المانجو',
                'description' => 'Sweet and tropical mango juice.',
                'price' => 1.99,
                'image' => 'https://example.com/images/mango.jpg',
                'is_available' => true,
                'recipe_id' => Recipe::where('name', 'Jus de mangue - عصير المانجو')->first()->id ?? null
            ],
            // Mixed Fruit Smoothies
            [
                'name' => 'Smoothie Tropical - سموذي استوائي',
                'description' => 'Refreshing blend of mango, pineapple juice and milk.',
                'price' => 5.99,
                'image' => 'https://example.com/images/tropical_smoothie.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Smoothie Banane-Orange - سموذي موز وبرتقال',
                'description' => 'Creamy blend of banana and orange juice with milk.',
                'price' => 5.49,
                'image' => 'https://example.com/images/banana_orange_smoothie.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            // Layered Juices
            [
                'name' => 'Sunrise Layered - عصير الشروق',
                'description' => 'Beautiful layered juice with dragon fruit, mango and orange.',
                'price' => 6.99,
                'image' => 'https://example.com/images/sunrise_layered.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            // Energy Smoothies
            [
                'name' => 'Smoothie Énergie Verte - سموذي الطاقة الأخضر',
                'description' => 'Nutrient-rich smoothie with avocado, banana and milk.',
                'price' => 6.49,
                'image' => 'https://example.com/images/green_energy.jpg',
                'is_available' => true,
                'recipe_id' => null
            ]
        ];

        foreach ($fruits as $fruit) {
            Product::create(array_merge($fruit, ['category_id' => $fruitsCategory->id]));
        }

        // Dried Fruits Category
        $driedFruitsCategory = Category::firstOrCreate([
            'id' => 3,
            'slug' => 'dried-fruits',
            'name' => 'Dried Fruits'
        ]);

        $driedFruits = [
            [
                'name' => 'Dried Candied Orange',
                'description' => 'Sweet and tangy dried candied orange slices.',
                'price' => 2.99,
                'image' => 'https://example.com/images/dried_candied_orange.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Apple',
                'description' => 'Crispy and flavorful dried apple slices.',
                'price' => 1.99,
                'image' => 'https://example.com/images/dried_apple.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Pineapple',
                'description' => 'Sweet and chewy dried pineapple chunks.',
                'price' => 2.49,
                'image' => 'https://example.com/images/dried_pineapple.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Dragon Fruit',
                'description' => 'Unique and flavorful dried dragon fruit slices.',
                'price' => 3.49,
                'image' => 'https://example.com/images/dried_dragon_fruit.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Papaya',
                'description' => 'Soft and sweet dried papaya strips.',
                'price' => 2.29,
                'image' => 'https://example.com/images/dried_papaya.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Kiwi',
                'description' => 'Tart and tangy dried kiwi slices.',
                'price' => 2.79,
                'image' => 'https://example.com/images/dried_kiwi.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Dried Lemon',
                'description' => 'Zesty and sour dried lemon slices.',
                'price' => 1.79,
                'image' => 'https://example.com/images/dried_lemon.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            // Mixed Dried Fruit Packs
            [
                'name' => 'Mix Tropical Séché - مزيج الفواكه المجففة الاستوائية',
                'description' => 'Delicious mix of dried pineapple, dragon fruit, and kiwi.',
                'price' => 4.99,
                'image' => 'https://example.com/images/tropical_mix.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Mix Fruits Séchés Premium - مزيج الفواكه المجففة الممتازة',
                'description' => 'Premium mix of dried apple, orange, and papaya.',
                'price' => 5.49,
                'image' => 'https://example.com/images/premium_mix.jpg',
                'is_available' => true,
                'recipe_id' => null
            ]
        ];

        foreach ($driedFruits as $driedFruit) {
            Product::create(array_merge($driedFruit, ['category_id' => $driedFruitsCategory->id]));
        }

        // Base Category
        $baseCategory = Category::firstOrCreate([
            'id' => 4,
            'slug' => 'base',
            'name' => 'Base'
        ]);

        $baseJuices = [
            [
                'name' => 'Lait - حليب',
                'description' => 'Fresh milk from local farms.',
                'price' => 1.99,
                'image' => 'https://example.com/images/milk.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Jus d\'orange - عصير البرتقال',
                'description' => 'Fresh orange juice.',
                'price' => 1.99,
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            [
                'name' => 'Eau Glacée - ماء بارد',
                'description' => 'Ice cold water.',
                'price' => 1.99,
                'image' => 'https://example.com/images/water.jpg',
                'is_available' => true,
                'recipe_id' => null
            ],
            // Fruit Bowl Category
            [
                'name' => 'Fruits sechés - وعاء الفواكه المجففة',
                'description' => 'Fresh fruit bowl with apple, peach, and dried fruit toppings.',
                'price' => 7.99,
                'image' => 'https://example.com/images/fruit_bowl.jpg',
                'is_available' => true,
                'recipe_id' => null
            ]
        ];

        foreach ($baseJuices as $baseJuice) {
            Product::create(array_merge($baseJuice, ['category_id' => $baseCategory->id]));
        }
    }
}
