<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Enums\UnitType;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            // Simple Fruit Juices
            [
                'name' => 'Jus de pomme - عصير التفاح',
                'description' => 'Fresh apple juice',
                'instructions' => [
                    'Wash and cut apples',
                    'Extract juice using juicer',
                    'Strain if needed',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 95,
                    'protein' => 0.5,
                    'carbs' => 25,
                    'fat' => 0.3
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Pomme - تفاح')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'washed and cut'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de banane - عصير الموز',
                'description' => 'Creamy banana juice',
                'instructions' => [
                    'Peel and slice bananas',
                    'Blend with milk',
                    'Add ice',
                    'Serve immediately'
                ],
                'preparation_time' => 8,
                'type' => 'juice',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 120,
                    'protein' => 2,
                    'carbs' => 28,
                    'fat' => 0.4
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 50,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => "Jus d'orange - عصير البرتقال",
                'description' => 'Fresh orange juice',
                'instructions' => [
                    'Wash oranges',
                    'Cut and juice oranges',
                    'Strain pulp if desired',
                    'Add ice and serve'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 85,
                    'protein' => 1.7,
                    'carbs' => 21,
                    'fat' => 0.2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Orange - برتقال')->first()->id,
                        'quantity' => 400,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'washed'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de pêche - عصير الخوخ',
                'description' => 'Refreshing peach juice',
                'instructions' => [
                    'Wash and slice peaches',
                    'Blend with water',
                    'Strain if desired',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 60,
                    'protein' => 1,
                    'carbs' => 15,
                    'fat' => 0.2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Peach - الخوخ')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'washed and sliced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de mangue - عصير المانجو',
                'description' => 'Sweet mango juice',
                'instructions' => [
                    'Peel and chop mangoes',
                    'Blend with water',
                    'Strain if desired',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 100,
                    'protein' => 1,
                    'carbs' => 25,
                    'fat' => 0.4
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de poire - عصير الاجاص',
                'description' => 'Delicate pear juice',
                'instructions' => [
                    'Wash and slice pears',
                    'Blend with water',
                    'Strain if desired',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 70,
                    'protein' => 0.5,
                    'carbs' => 18,
                    'fat' => 0.1
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Pear - الاجاص')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'washed and sliced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de papaye - عصير البابايا',
                'description' => 'Tropical papaya juice',
                'instructions' => [
                    'Peel and chop papayas',
                    'Blend with water',
                    'Strain if desired',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 80,
                    'protein' => 1,
                    'carbs' => 20,
                    'fat' => 0.3
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Papaya - البابايا')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Jus de kiwi - عصير الكيوي',
                'description' => 'Tangy kiwi juice',
                'instructions' => [
                    'Peel and slice kiwis',
                    'Blend with water',
                    'Strain if desired',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'juice',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 50,
                    'protein' => 1,
                    'carbs' => 12,
                    'fat' => 0.2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Kiwi - كيوي')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and sliced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // Mixed Juices
            [
                'name' => 'Panache - باناش',
                'description' => 'Mixed fruit juice blend',
                'instructions' => [
                    'Prepare all fruits',
                    'Blend fruits together',
                    'Add ice',
                    'Serve immediately'
                ],
                'preparation_time' => 15,
                'type' => 'juice',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 120,
                    'protein' => 1.5,
                    'carbs' => 30,
                    'fat' => 0.5
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Orange - برتقال')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Ananas - اناناس')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'cored and cut'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // Smoothies
            [
                'name' => 'Smoothie Tropical - سموذي استوائي',
                'description' => 'Tropical fruit smoothie',
                'instructions' => [
                    'Cut and prepare fruits',
                    'Blend with milk and ice',
                    'Add sugar if needed',
                    'Serve in a tall glass'
                ],
                'preparation_time' => 12,
                'type' => 'smoothie',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 180,
                    'protein' => 3,
                    'carbs' => 35,
                    'fat' => 2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and cut'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Ananas - اناناس')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'cored and cut'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Sucre - سكر')->first()->id,
                        'quantity' => 20,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'optional'
                    ]
                ]
            ],
            [
                'name' => 'Smoothie Banane-Orange - سموذي موز وبرتقال',
                'description' => 'Creamy banana-orange smoothie',
                'instructions' => [
                    'Peel and slice banana',
                    'Juice oranges',
                    'Blend with milk and ice',
                    'Add sugar to taste',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 160,
                    'protein' => 3.5,
                    'carbs' => 32,
                    'fat' => 1.5
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Orange - برتقال')->first()->id,
                        'quantity' => 300,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'juiced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Sucre - سكر')->first()->id,
                        'quantity' => 15,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'optional'
                    ]
                ]
            ],
            // Special Drinks
            [
                'name' => 'Sunrise Layered - عصير الشروق',
                'description' => 'Beautiful layered fruit juice',
                'instructions' => [
                    'Prepare each fruit juice separately',
                    'Layer carefully in glass',
                    'Start with dragon fruit',
                    'Add mango layer',
                    'Finish with orange layer',
                    'Serve immediately'
                ],
                'preparation_time' => 20,
                'type' => 'special',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 150,
                    'protein' => 2,
                    'carbs' => 35,
                    'fat' => 0.5
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Dragon Fruit - فاكهة التنين')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and blended'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and blended'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Orange - برتقال')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'juiced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // Additional Smoothies
            [
                'name' => 'Smoothie Énergie Verte - سموذي الطاقة الأخضر',
                'description' => 'Nutrient-rich smoothie with avocado, banana, and milk.',
                'instructions' => [
                    'Peel and slice avocado and banana',
                    'Blend with milk and ice',
                    'Serve immediately'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 250,
                    'protein' => 4,
                    'carbs' => 30,
                    'fat' => 12
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Avocate - الافوكادو')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and sliced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Smoothie Tropical Banane - سموذي استوائي بالموز',
                'description' => 'Creamy banana smoothie with tropical flavors.',
                'instructions' => [
                    'Peel and slice banana',
                    'Blend with mango and pineapple',
                    'Add milk and ice',
                    'Serve chilled'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 220,
                    'protein' => 3,
                    'carbs' => 40,
                    'fat' => 2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Ananas - اناناس')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'cored and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // Additional Special Drinks
            [
                'name' => 'Smoothie de Fruits Secs - سموذي بالفواكه المجففة',
                'description' => 'Nutritious smoothie with dried fruits.',
                'instructions' => [
                    'Soak dried fruits in water for 30 minutes',
                    'Blend with milk and ice',
                    'Serve chilled'
                ],
                'preparation_time' => 15,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 200,
                    'protein' => 3,
                    'carbs' => 45,
                    'fat' => 1
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Fruits Secs - فواكه مجففة')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'soaked'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // Additional Juice Recipes
            // [
            //     'name' => 'Jus de fraise - عصير الفراولة',
            //     'description' => 'Refreshing strawberry juice',
            //     'instructions' => [
            //         'Wash and hull strawberries',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 50,
            //         'protein' => 1,
            //         'carbs' => 12,
            //         'fat' => 0.1
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Fraise - فراولة')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and hulled'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de cerise - عصير الكرز',
            //     'description' => 'Sweet cherry juice',
            //     'instructions' => [
            //         'Wash and pit cherries',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 70,
            //         'protein' => 1,
            //         'carbs' => 18,
            //         'fat' => 0.2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Cerise - كرز')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and pitted'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de framboise - عصير التوت',
            //     'description' => 'Tangy raspberry juice',
            //     'instructions' => [
            //         'Wash raspberries',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 60,
            //         'protein' => 1,
            //         'carbs' => 14,
            //         'fat' => 0.1
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Framboise - توت العليق')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de melon - عصير الشمام',
            //     'description' => 'Sweet melon juice',
            //     'instructions' => [
            //         'Peel and chop melon',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 50,
            //         'protein' => 1,
            //         'carbs' => 13,
            //         'fat' => 0.2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Melon - شمام')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'peeled and chopped'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de grenade - عصير الرمان',
            //     'description' => 'Rich pomegranate juice',
            //     'instructions' => [
            //         'Cut and seed pomegranates',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 15,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 80,
            //         'protein' => 1,
            //         'carbs' => 20,
            //         'fat' => 0.3
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Grenade - رمان')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'cut and seeded'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de concombre - عصير الخيار',
            //     'description' => 'Refreshing cucumber juice',
            //     'instructions' => [
            //         'Wash and slice cucumbers',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 16,
            //         'protein' => 0.7,
            //         'carbs' => 4,
            //         'fat' => 0.1
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Concombre - خيار')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and sliced'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de carotte - عصير الجزر',
            //     'description' => 'Sweet carrot juice',
            //     'instructions' => [
            //         'Wash and chop carrots',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 41,
            //         'protein' => 0.9,
            //         'carbs' => 10,
            //         'fat' => 0.2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Carotte - جزر')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and chopped'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de betterave - عصير الشمندر',
            //     'description' => 'Earthy beet juice',
            //     'instructions' => [
            //         'Wash and chop beets',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 43,
            //         'protein' => 1.6,
            //         'carbs' => 10,
            //         'fat' => 0.2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Betterave - شمندر')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and chopped'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Jus de tomate - عصير الطماطم',
            //     'description' => 'Savory tomato juice',
            //     'instructions' => [
            //         'Wash and chop tomatoes',
            //         'Blend with water',
            //         'Strain if desired',
            //         'Serve chilled'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'juice',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 18,
            //         'protein' => 0.9,
            //         'carbs' => 4,
            //         'fat' => 0.2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Tomate - طماطم')->first()->id,
            //             'quantity' => 300,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed and chopped'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // Additional Smoothie Recipes
            [
                'name' => 'Smoothie de Mangue et Avocat - سموذي المانجو والأفوكادو',
                'description' => 'Creamy mango and avocado smoothie.',
                'instructions' => [
                    'Peel and chop mango and avocado.',
                    'Blend with milk and ice.',
                    'Serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 300,
                    'protein' => 4,
                    'carbs' => 40,
                    'fat' => 15
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Avocate - الافوكادو')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            // [
            //     'name' => 'Smoothie de Banane et Kiwi - سموذي الموز والكيوي',
            //     'description' => 'Refreshing banana and kiwi smoothie.',
            //     'instructions' => [
            //         'Peel and slice banana and kiwi.',
            //         'Blend with yogurt and ice.',
            //         'Serve chilled.'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'smoothie',
            //     'is_featured' => false,
            //     'nutritional_info' => [
            //         'calories' => 220,
            //         'protein' => 3,
            //         'carbs' => 40,
            //         'fat' => 1
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
            //             'quantity' => 150,
            //             'unit' => 'g',
            //             'preparation_notes' => 'peeled'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Kiwi - كيوي')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'g',
            //             'preparation_notes' => 'peeled and sliced'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Yogourt - زبادي')->first()->id,
            //             'quantity' => 150,
            //             'unit' => 'g',
            //             'preparation_notes' => null
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'ml',
            //             'preparation_notes' => null
            //         ]
            //     ]
            // ],
            // [
            //     'name' => 'Smoothie de Fruits Rouges - سموذي بالفواكه الحمراء',
            //     'description' => 'Mixed berry smoothie.',
            //     'instructions' => [
            //         'Blend strawberries, raspberries, and blueberries with yogurt.',
            //         'Add honey for sweetness.',
            //         'Serve chilled.'
            //     ],
            //     'preparation_time' => 10,
            //     'type' => 'smoothie',
            //     'is_featured' => true,
            //     'nutritional_info' => [
            //         'calories' => 180,
            //         'protein' => 4,
            //         'carbs' => 35,
            //         'fat' => 2
            //     ],
            //     'ingredients' => [
            //         [
            //             'id' => Ingredient::where('name', 'Fraise - فراولة')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Framboise - توت العليق')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Bleuet - توت أزرق')->first()->id,
            //             'quantity' => 100,
            //             'unit' => 'g',
            //             'preparation_notes' => 'washed'
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Yogourt - زبادي')->first()->id,
            //             'quantity' => 150,
            //             'unit' => 'g',
            //             'preparation_notes' => null
            //         ],
            //         [
            //             'id' => Ingredient::where('name', 'Miel - عسل')->first()->id,
            //             'quantity' => 10,
            //             'unit' => 'g',
            //             'preparation_notes' => 'optional'
            //         ]
            //     ]
            // ],
            [
                'name' => 'Smoothie Tropical - سموذي استوائي',
                'description' => 'Tropical smoothie with mango and pineapple.',
                'instructions' => [
                    'Blend mango, pineapple, and coconut milk.',
                    'Add ice and serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 250,
                    'protein' => 3,
                    'carbs' => 45,
                    'fat' => 5
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Mangue - المانجو')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Ananas - اناناس')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'cored and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait de Coco - حليب جوز الهند')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Smoothie de Banane et Avoine - سموذي الموز والشوفان',
                'description' => 'Nutritious banana and oat smoothie.',
                'instructions' => [
                    'Blend banana, oats, and milk.',
                    'Add honey for sweetness.',
                    'Serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 220,
                    'protein' => 5,
                    'carbs' => 40,
                    'fat' => 3
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Avoine - شوفان')->first()->id,
                        'quantity' => 50,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Miel - عسل')->first()->id,
                        'quantity' => 10,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'optional'
                    ]
                ]
            ],
            [
                'name' => 'Smoothie de Pêche et Yaourt - سموذي الخوخ والزبادي',
                'description' => 'Peach and yogurt smoothie.',
                'instructions' => [
                    'Blend peaches and yogurt.',
                    'Add ice and serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 180,
                    'protein' => 4,
                    'carbs' => 30,
                    'fat' => 2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Peach - الخوخ')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and sliced'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Yogourt - زبادي')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Smoothie de Papaye et Lait de Coco - سموذي البابايا وحليب جوز الهند',
                'description' => 'Tropical papaya and coconut milk smoothie.',
                'instructions' => [
                    'Blend papaya and coconut milk.',
                    'Add ice and serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 200,
                    'protein' => 2,
                    'carbs' => 35,
                    'fat' => 5
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Papaya - البابايا')->first()->id,
                        'quantity' => 150,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled and chopped'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait de Coco - حليب جوز الهند')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Smoothie de Lait et Banane - سموذي الحليب والموز',
                'description' => 'Simple banana and milk smoothie.',
                'instructions' => [
                    'Blend banana and milk.',
                    'Add ice and serve chilled.'
                ],
                'preparation_time' => 5,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 150,
                    'protein' => 3,
                    'carbs' => 30,
                    'fat' => 2
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Bannane - الموز')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'peeled'
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Glace - الماء مثلج')->first()->id,
                        'quantity' => 100,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ]
                ]
            ],
            [
                'name' => 'Smoothie de Lait et Avoine - سموذي الحليب والشوفان',
                'description' => 'Nutritious milk and oat smoothie.',
                'instructions' => [
                    'Blend oats and milk.',
                    'Add honey for sweetness.',
                    'Serve chilled.'
                ],
                'preparation_time' => 10,
                'type' => 'smoothie',
                'is_featured' => false,
                'nutritional_info' => [
                    'calories' => 220,
                    'protein' => 5,
                    'carbs' => 40,
                    'fat' => 3
                ],
                'ingredients' => [
                    [
                        'id' => Ingredient::where('name', 'Avoine - شوفان')->first()->id,
                        'quantity' => 50,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Lait - حليب')->first()->id,
                        'quantity' => 200,
                        'unit' => UnitType::Mililitre->value,
                        'preparation_notes' => null
                    ],
                    [
                        'id' => Ingredient::where('name', 'Miel - عسل')->first()->id,
                        'quantity' => 10,
                        'unit' => UnitType::Gram->value,
                        'preparation_notes' => 'optional'
                    ]
                ]
            ],
        ];

        foreach ($recipes as $recipeData) {
            $ingredients = $recipeData['ingredients'];
            unset($recipeData['ingredients']);

            $recipe = Recipe::create($recipeData);

            foreach ($ingredients as $ingredient) {
                $recipe->ingredients()->attach($ingredient['id'], [
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'preparation_notes' => $ingredient['preparation_notes']
                ]);
            }
        }
    }
}
