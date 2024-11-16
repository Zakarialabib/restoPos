<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Price;
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
            'name' => 'Fruits'
        ]);

        $fruits = [
            [
                'name' => 'Jus de pomme - عصير التفاح',
                'description' => 'Fresh, crisp apples perfect for snacking or baking.',
                'image' => 'https://example.com/images/apple.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de pomme - عصير التفاح')->first()->id ?? null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Panache - باناش',
                'description' => 'Fresh fruit mix juice',
                'image' => 'https://example.com/images/panache.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de banane - عصير الموز',
                'description' => 'Ripe, yellow bananas rich in potassium.',
                'image' => 'https://example.com/images/banana.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => "Jus d'orange - عصير البرتقال",
                'description' => 'Juicy oranges packed with vitamin C.',
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 7.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 10.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 15.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus d\'ananas - عصير الاناناس',
                'description' => 'Sweet and tangy tropical pineapples.',
                'image' => 'https://example.com/images/pineapple.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 18.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 22.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 25.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de pêche - عصير الخوخ',
                'description' => 'Juicy and fragrant peaches, perfect for summer.',
                'image' => 'https://example.com/images/peach.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus d\'avocat - عصير الافوكادو',
                'description' => 'Creamy and nutritious avocados, great for salads and toast.',
                'image' => 'https://example.com/images/avocado.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de poire - عصير الاجاص',
                'description' => 'Juicy and sweet pears with a delicate flavor.',
                'image' => 'https://example.com/images/pear.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de poire - عصير الاجاص')->first()->id ?? null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de dragon fruit - عصير فاكهة التنين',
                'description' => 'Exotic dragon fruit with a unique appearance and mild, sweet taste.',
                'image' => 'https://example.com/images/dragon_fruit.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 18.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 25.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 30.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de papaye - عصير البابايا',
                'description' => 'Tropical papaya with a sweet, musky flavor and soft texture.',
                'image' => 'https://example.com/images/papaya.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de mangue - عصير المانجو',
                'description' => 'Sweet and tropical mango juice.',
                'image' => 'https://example.com/images/mango.jpg',
                'is_available' => true,
                'category_id' => $fruitsCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de mangue - عصير المانجو')->first()->id ?? null,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            // Mixed Fruit Smoothies
            [
                'name' => 'Smoothie Tropical - سموذي استوائي',
                'description' => 'Refreshing blend of mango, pineapple juice and milk.',
                'image' => 'https://example.com/images/tropical_smoothie.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $fruitsCategory->id,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Smoothie Banane-Orange - سموذي موز وبرتقال',
                'description' => 'Creamy blend of banana and orange juice with milk.',
                'image' => 'https://example.com/images/banana_orange_smoothie.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $fruitsCategory->id,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            // Layered Juices
            [
                'name' => 'Sunrise Layered - عصير الشروق',
                'description' => 'Beautiful layered juice with dragon fruit, mango and orange.',
                'image' => 'https://example.com/images/sunrise_layered.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $fruitsCategory->id,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            // Energy Smoothies
            [
                'name' => 'Smoothie Énergie Verte - سموذي الطاقة الأخضر',
                'description' => 'Nutrient-rich smoothie with avocado, banana and milk.',
                'image' => 'https://example.com/images/green_energy.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $fruitsCategory->id,
                'prices' => [
                    ['size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' =>  __('Large size')],
                ]
            ]
        ];

        foreach ($fruits as $fruitData) {
            $prices = $fruitData['prices'] ?? [];
            unset($fruitData['prices']);

            $product = Product::create($fruitData);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'date' => now(),
                    'notes' => $priceData['notes'] ?? null,
                    'metadata' => [ // Changed 'prices' to 'metadata'
                        'size' => $priceData['size'],
                        'unit' => $priceData['unit'] ?? 'default',
                    ],
                ]);
            }
        }

        // Dried Fruits Category
        $driedFruitsCategory = Category::firstOrCreate([
            'id' => 3,
            'name' => 'Dried Fruits'
        ]);

        $driedFruits = [
            [
                'name' => 'Dried Candied Orange',
                'description' => 'Sweet and tangy dried candied orange slices.',
                'image' => 'https://example.com/images/dried_candied_orange.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Apple',
                'description' => 'Crispy and flavorful dried apple slices.',
                'image' => 'https://example.com/images/dried_apple.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Pineapple',
                'description' => 'Sweet and chewy dried pineapple chunks.',
                'image' => 'https://example.com/images/dried_pineapple.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Dragon Fruit',
                'description' => 'Unique and flavorful dried dragon fruit slices.',
                'image' => 'https://example.com/images/dried_dragon_fruit.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Papaya',
                'description' => 'Soft and sweet dried papaya strips.',
                'image' => 'https://example.com/images/dried_papaya.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Dried Kiwi',
                'description' => 'Tart and tangy dried kiwi slices.',
                'image' => 'https://example.com/images/dried_kiwi.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Dried Lemon',
                'description' => 'Zesty and sour dried lemon slices.',
                'image' => 'https://example.com/images/dried_lemon.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            // Mixed Dried Fruit Packs
            [
                'name' => 'Mix Tropical Séché - مزيج الفواكه المجففة الاستوائية',
                'description' => 'Delicious mix of dried pineapple, dragon fruit, and kiwi.',
                'image' => 'https://example.com/images/tropical_mix.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Mix Fruits Séchés Premium - مزيج الفواكه المجففة الممتازة',
                'description' => 'Premium mix of dried apple, orange, and papaya.',
                'image' => 'https://example.com/images/premium_mix.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ]
        ];

        foreach ($driedFruits as $driedFruit) {
            $prices = $driedFruit['prices'] ?? [];
            unset($driedFruit['prices']);

            $product = Product::create($driedFruit);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'date' => now(),
                    'notes' => $priceData['notes'] ?? null,
                    'metadata' => [ // Changed 'prices' to 'metadata'
                        'size' => $priceData['size'],
                        'unit' => $priceData['unit'] ?? 'default',
                    ],
                ]);
            }
        }

        // Base Category
        $baseCategory = Category::firstOrCreate([
            'id' => 4,
            'name' => 'Base'
        ]);

        $baseJuices = [
            [
                'name' => 'Lait - حليب',
                'description' => 'Fresh milk from local farms.',
                'image' => 'https://example.com/images/milk.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $baseCategory->id,

                'prices' => [
                    ['size' => __('small'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Jus d\'orange - عصير البرتقال',
                'description' => 'Fresh orange juice.',
                'image' => 'https://example.com/images/orange.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $baseCategory->id,

                'prices' => [
                    ['size' => __('small'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Eau Glacée - ماء بارد',
                'description' => 'Ice cold water.',
                'image' => 'https://example.com/images/water.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $baseCategory->id,

                'prices' => [
                    ['size' => __('small'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            // Fruit Bowl Category
            [
                'name' => 'Fruits sechés - وعاء الفواكه المجففة',
                'description' => 'Fresh fruit bowl with apple, peach, and dried fruit toppings.',
                'image' => 'https://example.com/images/fruit_bowl.jpg',
                'is_available' => true,
                'recipe_id' => null,
                'category_id' => $baseCategory->id,

                'prices' => [
                    ['size' => __('small'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ]
        ];

        foreach ($baseJuices as $baseJuice) {
            $prices = $baseJuice['prices'] ?? [];
            unset($baseJuice['prices']);

            $product = Product::create($baseJuice);

            // $baseCategory->products()->attach($product);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'date' => now(),
                    'notes' => $priceData['notes'] ?? null,
                    'metadata' => [ // Changed 'prices' to 'metadata'
                        'size' => $priceData['size'],
                        'unit' => $priceData['unit'] ?? 'default',
                    ],
                ]);
            }
        }
    }
}
