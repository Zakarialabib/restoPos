<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'sku' => $this->faker->unique()->isbn10(),
            'category_id' => Category::factory(),
            'stock_quantity' => $this->faker->randomFloat(2, 0, 1000),
            'unit' => $this->faker->randomElement(['kg', 'g', 'ml', 'l', 'piece']),
            'cost_per_unit' => $this->faker->randomFloat(2, 1, 100),
            'reorder_point' => $this->faker->randomFloat(2, 10, 50),
            'status' => $this->faker->boolean(80),
            'is_seasonal' => $this->faker->boolean(20),
            'nutritional_info' => [
                'calories' => $this->faker->randomFloat(2, 0, 500),
                'protein' => $this->faker->randomFloat(2, 0, 50),
                'carbs' => $this->faker->randomFloat(2, 0, 100),
                'fat' => $this->faker->randomFloat(2, 0, 50)
            ]
        ];
    }

    public function fruit(): self
    {
        return $this->state(function () {
            return [
                'category_id' => Category::firstOrCreate([
                    'name' => 'Fruits',
                    'description' => 'Fresh fruits'
                ])->id
            ];
        });
    }

    public function vegetable(): self
    {
        return $this->state(function () {
            return [
                'category_id' => Category::firstOrCreate([
                    'name' => 'Vegetables',
                    'description' => 'Fresh vegetables'
                ])->id
            ];
        });
    }

    public function supplement(): self
    {
        return $this->state(function () {
            return [
                'category_id' => Category::firstOrCreate([
                    'name' => 'Supplements',
                    'description' => 'Nutritional supplements'
                ])->id
            ];
        });
    }

    public function lowStock(): self
    {
        return $this->state(function () {
            return [
                'stock_quantity' => $this->faker->randomFloat(2, 0, 10)
            ];
        });
    }

    public function outOfStock(): self
    {
        return $this->state(function () {
            return [
                'stock_quantity' => 0
            ];
        });
    }

    public function seasonal(): self
    {
        return $this->state(function () {
            return [
                'is_seasonal' => true
            ];
        });
    }
}
