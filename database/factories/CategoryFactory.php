<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'id' => fake()->unique()->numberBetween(1, 100),
            'name' => $name,
            'description' => fake()->sentence(),
            'status' => fake()->boolean(90), // 90% chance of being active
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }
}
