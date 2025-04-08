<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Fruits Category
        $juicesCategory = Category::where('name', 'Juices')->first();
        if ( ! $juicesCategory) {
            $juicesCategory = Category::create([
                'name' => 'Juices',
                'status' => 1
            ]);
        }

        $fruits = [
            [
                'name' => 'Jus de pomme - عصير التفاح',
                'description' => 'Fresh, crisp apples perfect for snacking or baking.',
                'image' => 'https://example.com/images/apple.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de pomme - عصير التفاح')->first()->id ?? null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Panache - باناش',
                'description' => 'Fresh fruit mix juice',
                'image' => 'https://example.com/images/panache.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de banane - عصير الموز',
                'description' => 'Ripe, yellow bananas rich in potassium.',
                'image' => 'https://example.com/images/banana.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => "Jus d'orange - عصير البرتقال",
                'description' => 'Juicy oranges packed with vitamin C.',
                'image' => 'https://example.com/images/orange.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 7.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 10.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 15.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus d\'ananas - عصير الاناناس',
                'description' => 'Sweet and tangy tropical pineapples.',
                'image' => 'https://example.com/images/pineapple.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 18.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 22.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 25.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de pêche - عصير الخوخ',
                'description' => 'Juicy and fragrant peaches, perfect for summer.',
                'image' => 'https://example.com/images/peach.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus d\'avocat - عصير الافوكادو',
                'description' => 'Creamy and nutritious avocados, great for salads and toast.',
                'image' => 'https://example.com/images/avocado.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de poire - عصير الاجاص',
                'description' => 'Juicy and sweet pears with a delicate flavor.',
                'image' => 'https://example.com/images/pear.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de poire - عصير الاجاص')->first()->id ?? null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de dragon fruit - عصير فاكهة التنين',
                'description' => 'Exotic dragon fruit with a unique appearance and mild, sweet taste.',
                'image' => 'https://example.com/images/dragon_fruit.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 18.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 25.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 30.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de papaye - عصير البابايا',
                'description' => 'Tropical papaya with a sweet, musky flavor and soft texture.',
                'image' => 'https://example.com/images/papaya.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Jus de mangue - عصير المانجو',
                'description' => 'Sweet and tropical mango juice.',
                'image' => 'https://example.com/images/mango.jpg',
                'status' => true,
                'category_id' => $juicesCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de mangue - عصير المانجو')->first()->id ?? null,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 22.00, 'notes' => __('Large size')],
                ]
            ],
            // Mixed Fruit Smoothies
            [
                'name' => 'Smoothie Tropical - سموذي استوائي',
                'description' => 'Refreshing blend of mango, pineapple juice and milk.',
                'image' => 'https://example.com/images/tropical_smoothie.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $juicesCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Smoothie Banane-Orange - سموذي موز وبرتقال',
                'description' => 'Creamy blend of banana and orange juice with milk.',
                'image' => 'https://example.com/images/banana_orange_smoothie.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $juicesCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            // Layered Juices
            [
                'name' => 'Sunrise Layered - عصير الشروق',
                'description' => 'Beautiful layered juice with dragon fruit, mango and orange.',
                'image' => 'https://example.com/images/sunrise_layered.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $juicesCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' => __('Large size')],
                ]
            ],
            // Energy Smoothies
            [
                'name' => 'Smoothie Énergie Verte - سموذي الطاقة الأخضر',
                'description' => 'Nutrient-rich smoothie with avocado, banana and milk.',
                'image' => 'https://example.com/images/green_energy.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $juicesCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 12.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 15.00, 'price' => 15.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 18.00, 'price' => 18.00, 'notes' =>  __('Large size')],
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
                    'entry_date' => now(),
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
            'id' => 99,
            'name' => 'Dried Fruits',
            'status' => 1
        ]);

        $driedFruits = [
            [
                'name' => 'Dried Candied Orange',
                'description' => 'Sweet and tangy dried candied orange slices.',
                'image' => 'https://example.com/images/dried_candied_orange.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Apple',
                'description' => 'Crispy and flavorful dried apple slices.',
                'image' => 'https://example.com/images/dried_apple.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Pineapple',
                'description' => 'Sweet and chewy dried pineapple chunks.',
                'image' => 'https://example.com/images/dried_pineapple.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Dragon Fruit',
                'description' => 'Unique and flavorful dried dragon fruit slices.',
                'image' => 'https://example.com/images/dried_dragon_fruit.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Dried Papaya',
                'description' => 'Soft and sweet dried papaya strips.',
                'image' => 'https://example.com/images/dried_papaya.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Dried Kiwi',
                'description' => 'Tart and tangy dried kiwi slices.',
                'image' => 'https://example.com/images/dried_kiwi.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Dried Lemon',
                'description' => 'Zesty and sour dried lemon slices.',
                'image' => 'https://example.com/images/dried_lemon.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            // Mixed Dried Fruit Packs
            [
                'name' => 'Mix Tropical Séché - مزيج الفواكه المجففة الاستوائية',
                'description' => 'Delicious mix of dried pineapple, dragon fruit, and kiwi.',
                'image' => 'https://example.com/images/tropical_mix.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ],
            ],
            [
                'name' => 'Mix Fruits Séchés Premium - مزيج الفواكه المجففة الممتازة',
                'description' => 'Premium mix of dried apple, orange, and papaya.',
                'image' => 'https://example.com/images/premium_mix.jpg',
                'status' => true,
                'recipe_id' => null,
                'category_id' => $driedFruitsCategory->id,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small 250gr'), 'cost' => 10.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium 500gr'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large 1kg'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
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
                    'entry_date' => now(),
                    'notes' => $priceData['notes'] ?? null,
                    'metadata' => [ // Changed 'prices' to 'metadata'
                        'size' => $priceData['size'],
                        'unit' => $priceData['unit'] ?? 'default',
                    ],
                ]);
            }
        }

        // check if Salads Category already created in category seeder
        $saladsCategory = Category::where('name', 'Salad')->first();
        if ( ! $saladsCategory) {
            $saladsCategory = Category::create([
                'name' => 'Salad',
                'status' => 1
            ]);
        }
        $salades = [

            [
                'name' => 'Bakoula - بقولة',
                'description' => 'Salade d\'épinards à la marocaine - سلطة السبانخ على الطريقة المغربية',
                'category_id' => $saladsCategory->id,
                'image' => 'products/bakoula.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]

            ],
            [
                'name' => 'Carotte Râpée -  جزر مبشور',
                'description' => 'Salade de carottes râpées à la marocaine - سلطة الجزر المبشور على الطريقة المغربية',
                'category_id' => $saladsCategory->id,
                'image' => 'products/carotte-rapee.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('small'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('medium'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                ]
            ],
        ];

        foreach ($salades as $salade) {
            $prices = $salade['prices'] ?? [];
            unset($salade['prices']);

            $product = Product::create($salade);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'entry_date' => now(),
                    'notes' => $priceData['notes'] ?? null,
                    'metadata' => [ // Changed 'prices' to 'metadata'
                        'size' => $priceData['size'],
                        'unit' => $priceData['unit'] ?? 'default',
                    ],
                ]);
            }
        }

        // check if Breads Category already created in category seeder
        $sandwichesCategory = Category::where('name', 'Sandwiches')->first();
        if ( ! $sandwichesCategory) {
            $sandwichesCategory = Category::create([
                'name' => 'Sandwiche',
                'status' => 1
            ]);
        }
        $sandwiches = [
            // More Moroccan Sandwiches
            [
                'name' => 'Sandwich Kefta - ساندويتش كفتة',
                'description' => 'Sandwich à la viande hachée épicée avec sauce harissa - ساندويتش بالكفتة المتبلة مع صلصة الهريسة',
                'category_id' => $sandwichesCategory->id,
                'image' => 'products/sandwich-kefta.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Sandwich Crevettes - ساندويتش قمرون',
                'description' => 'Sandwich aux crevettes grillées avec sauce cocktail - ساندويتش بالقمرون المشوي مع صلصة كوكتيل',
                'category_id' => $sandwichesCategory->id,
                'image' => 'products/sandwich-crevettes.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Sandwich Thon - ساندويتش تونة',
                'description' => 'Sandwich au thon avec salade et mayonnaise - ساندويتش بالتونة والسلطة والمايونيز',
                'category_id' => $sandwichesCategory->id,
                'image' => 'products/sandwich-thon.jpg',
                'status' => true,

                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Sandwich Poulet - ساندويتش دجاج',
                'description' => 'Sandwich au poulet grillé avec sauce harissa - ساندويتش بالدجاج المشوي مع صلصة الهريسة',
                'category_id' => $sandwichesCategory->id,
                'image' => 'products/sandwich-poulet.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
            [
                'name' => 'Sandwich Viande - ساندويتش لحم',
                'description' => 'Sandwich à la viande hachée épicée - ساندويتش باللحم المفروم المتبل',
                'category_id' => $sandwichesCategory->id,
                'image' => 'products/sandwich-viande.jpg',
                'status' => true,
                'prices' => [
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('normal'), 'cost' => 8.00, 'price' => 15.00, 'notes' => __('Small size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('large'), 'cost' => 12.00, 'price' => 18.00, 'notes' => __('Medium size')],
                    ['entry_date' => Carbon::now()->format('Y-m-d'), 'size' => __('special'), 'cost' => 15.00, 'price' => 20.00, 'notes' => __('Large size')],
                ]
            ],
        ];
        foreach ($sandwiches as $sandwich) {
            $prices = $sandwich['prices'] ?? [];
            unset($sandwich['prices']);

            $product = Product::create($sandwich);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'entry_date' => now(),
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
