<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Composable;
use Illuminate\Database\Seeder;

class ComposableProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::with('ingredients')->get();

        // Assuming you have some products that are composable
        // For demonstration, let's create some dummy composables
        // In a real application, these would likely be linked to actual products
        for ($i = 0; $i < 10; $i++) {
            $category = $categories->random();
            $composable = Composable::create([
                'name' => 'Composable Item ' . ($i + 1),
                'description' => 'Description for composable item ' . ($i + 1),
                'price' => rand(10, 50),
                'type' => 'product',
                'category_id' => $category->id,
                'configuration_rules' => json_encode(['customizable' => true]),
                'min_ingredients' => 1,
                'max_ingredients' => 5,
                'base_required' => false,
                'base_price' => rand(10, 50),
                'status' => true
            ]);

            $ingredients = $category->ingredients()->inRandomOrder()->take(rand(1, 5))->get();
            foreach ($ingredients as $ingredient) {
                $composable->ingredients()->attach($ingredient->id, [
                    'quantity' => rand(1, 5),
                    'unit' => 'ml',
                ]);
            }
            $composable->save();
        }
    }
}
