<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'slug' => Str::slug('Jus de pomme - عصير التفاح'),
                'image' => 'https://example.com/images/apple.jpg',
                'status' => true,
                'status' => true,
                'is_featured' => false,
                'is_customizable' => false,
                'is_composable' => false,
                'base_price' => 15.00,
                'composable_rules' => null,
                'allergens' => null,
                'preparation_time' => 5,
                'stock_quantity' => 100,
                'reorder_point' => 20,
                'category_id' => $juicesCategory->id,
                'recipe_id' => Recipe::where('name', 'Jus de pomme - عصير التفاح')->first()->id ?? null,
                'prices' => [
                    [
                        'entry_date' => Carbon::now()->format('Y-m-d'),
                        'size' => 'small',
                        'cost' => 8.00,
                        'price' => 15.00,
                        'notes' => 'Small size',
                        'is_current' => true
                    ],
                    [
                        'entry_date' => Carbon::now()->format('Y-m-d'),
                        'size' => 'medium',
                        'cost' => 12.00,
                        'price' => 18.00,
                        'notes' => 'Medium size',
                        'is_current' => true
                    ],
                    [
                        'entry_date' => Carbon::now()->format('Y-m-d'),
                        'size' => 'large',
                        'cost' => 15.00,
                        'price' => 20.00,
                        'notes' => 'Large size',
                        'is_current' => true
                    ],
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

            // Ensure slug exists for firstOrCreate
            if ( ! isset($fruitData['slug'])) {
                $fruitData['slug'] = Str::slug($fruitData['name']);
            }
            $product = Product::firstOrCreate(['slug' => $fruitData['slug']], $fruitData);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'previous_cost' => 0,
                    'size' => $priceData['size'],
                    'entry_date' => $priceData['entry_date'],
                    'effective_date' => $priceData['entry_date'],
                    'notes' => $priceData['notes'],
                    // 'is_current' => $priceData['is_current'],
                    'metadata' => [
                        'unit' => 'piece',
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

            // Ensure slug exists for firstOrCreate
            if ( ! isset($driedFruit['slug'])) {
                $driedFruit['slug'] = Str::slug($driedFruit['name']);
            }
            $product = Product::firstOrCreate(['slug' => $driedFruit['slug']], $driedFruit);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'previous_cost' => 0,
                    'size' => $priceData['size'],
                    'entry_date' => $priceData['entry_date'],
                    'effective_date' => $priceData['entry_date'],
                    'notes' => $priceData['notes'],
                    'is_current' => true,
                    'metadata' => [
                        'unit' => 'piece',
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

            // Ensure slug exists for firstOrCreate
            if ( ! isset($salade['slug'])) {
                $salade['slug'] = Str::slug($salade['name']);
            }
            $product = Product::firstOrCreate(['slug' => $salade['slug']], $salade);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'previous_cost' => 0,
                    'size' => $priceData['size'],
                    'entry_date' => $priceData['entry_date'],
                    'effective_date' => $priceData['entry_date'],
                    'notes' => $priceData['notes'],
                    'is_current' => true,
                    'metadata' => [
                        'unit' => 'piece',
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

            // Ensure slug exists for firstOrCreate
            if ( ! isset($sandwich['slug'])) {
                $sandwich['slug'] = Str::slug($sandwich['name']);
            }
            $product = Product::firstOrCreate(['slug' => $sandwich['slug']], $sandwich);

            foreach ($prices as $priceData) {
                Price::create([
                    'priceable_type' => Product::class,
                    'priceable_id' => $product->id,
                    'cost' => $priceData['cost'],
                    'price' => $priceData['price'],
                    'previous_cost' => 0,
                    'size' => $priceData['size'],
                    'entry_date' => $priceData['entry_date'],
                    'effective_date' => $priceData['entry_date'],
                    'notes' => $priceData['notes'],
                    'is_current' => true,
                    'metadata' => [
                        'unit' => 'piece',
                    ],
                ]);
            }
        }

        $fruitBowlsCategory = Category::where('name', 'Fruit Bowls')->first();

        $products = [
            [
                'name' => 'Custom Fruit Bowl',
                'is_composable' => true,
                'base_price' => 15.00,
                'category_id' => $fruitBowlsCategory->id,
                'composable_rules' => [
                    'min_ingredients' => 3,
                    'max_ingredients' => 6,
                    'base_required' => true
                ]
            ]
        ];

        foreach ($products as $productData) {
            // Ensure slug exists for firstOrCreate
            if ( ! isset($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']);
            }
            $product = Product::firstOrCreate(['slug' => $productData['slug']], $productData);
        }
    }
}
