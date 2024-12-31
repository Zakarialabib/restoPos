<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'slug' => Str::slug($name),
            'category_id' => Category::factory(),
            'image' => 'products/' . $this->faker->image('public/storage/products', 640, 480, null, false),
            'status' => true,
            'is_featured' => false,
            'is_composable' => false,
            'min_stock' => $this->faker->numberBetween(5, 20),
            'nutritional_info' => [
                'calories' => $this->faker->numberBetween(50, 500),
                'protein' => $this->faker->numberBetween(0, 30),
                'carbs' => $this->faker->numberBetween(0, 50),
                'fat' => $this->faker->numberBetween(0, 20),
                'fiber' => $this->faker->numberBetween(0, 10),
                'vitamins' => [
                    'A' => $this->faker->numberBetween(0, 100),
                    'C' => $this->faker->numberBetween(0, 100),
                    'D' => $this->faker->numberBetween(0, 100),
                ],
            ],
            'preparation_time' => $this->faker->numberBetween(5, 30),
            'allergens' => $this->faker->randomElements(['nuts', 'dairy', 'soy', 'gluten'], $this->faker->numberBetween(0, 2)),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'status' => true,
        ]);
    }

    public function composable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_composable' => true,
            'status' => true,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'min_stock' => 10,
        ])->afterCreating(function (Product $product): void {
            $product->adjustStock(5, 'Initial stock');
        });
    }

    public function withPrice(?float $amount = null): static
    {
        return $this->afterCreating(function (Product $product) use ($amount): void {
            $product->setPrice($amount ?? $this->faker->numberBetween(5, 50));
        });
    }

    public function withIngredients(int $count = 3): static
    {
        return $this->afterCreating(function (Product $product) use ($count): void {
            $ingredients = \App\Models\Ingredient::factory()->count($count)->create();

            foreach ($ingredients as $ingredient) {
                $product->ingredients()->attach($ingredient, [
                    'quantity' => $this->faker->numberBetween(1, 5),
                    'unit' => $ingredient->unit,
                    'notes' => $this->faker->optional(0.3)->sentence(),
                ]);
            }
        });
    }

    public function withRecipe(): static
    {
        return $this->afterCreating(function (Product $product): void {
            Recipe::factory()
                ->for($product)
                ->create([
                    'instructions' => collect([
                        'Preparation' => $this->faker->sentences(2, true),
                        'Mixing' => $this->faker->sentences(2, true),
                        'Finishing' => $this->faker->sentences(1, true),
                    ])->toArray(),
                    'tips' => $this->faker->sentences(2, true),
                    'estimated_time' => $product->preparation_time,
                ]);
        });
    }

    public function seasonal(): static
    {
        return $this->state(function (array $attributes) {
            $season = $this->faker->randomElement(['Summer', 'Winter', 'Spring', 'Fall']);
            return [
                'name' => "{$season} " . $this->faker->words(2, true),
                'is_featured' => true,
                'description' => "Special {$season} " . $this->faker->sentence(),
            ];
        });
    }
}
