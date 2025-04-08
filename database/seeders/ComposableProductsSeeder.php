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

        Composable::chunk(200, function ($composables) use ($categories): void {
            foreach ($composables as $composable) {
                $category = $categories->random();
                $composable->category()->associate($category);

                $ingredients = $category->ingredients()->inRandomOrder()->take(5)->get();
                $composable->ingredients()->attach(
                    $ingredients->pluck('id')->toArray(),
                    ['quantity' => rand(1, 5), 'unit' => 'ml', 'is_optional' => false]
                );

                $composable->save();
            }
        });
    }
}
