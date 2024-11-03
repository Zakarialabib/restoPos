<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
                'name' => 'Classic Chocolate Cake',
                'description' => 'A rich and moist chocolate cake perfect for any occasion.',
                'instructions' => [
                    'Preheat oven to 350°F (175°C)',
                    'Mix dry ingredients in a bowl',
                    'Add wet ingredients and mix until smooth',
                    'Bake for 30-35 minutes'
                ],
                'preparation_time' => 60,
                'type' => 'dessert',
                'is_featured' => true,
                'nutritional_info' => [
                    'calories' => 350,
                    'protein' => 5,
                    'carbs' => 45,
                    'fat' => 18
                ],
                'ingredients' => [
                    ['id' => 1, 'quantity' => 250, 'unit' => 'g', 'preparation_notes' => 'sifted'],
                    ['id' => 2, 'quantity' => 100, 'unit' => 'g', 'preparation_notes' => null],
                    ['id' => 3, 'quantity' => 2, 'unit' => 'large', 'preparation_notes' => 'room temperature']
                ]
            ],
            // Add more recipes as needed
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
