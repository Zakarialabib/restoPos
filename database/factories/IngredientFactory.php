<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    public function definition(): array
    {
        $types = ['fruit', 'liquid', 'ice'];
        $units = ['g', 'ml', 'units'];

        return [
            'name' => fake()->unique()->word(),
            'type' => fake()->randomElement($types),
            'unit' => fake()->randomElement($units),
            'conversion_rate' => fake()->randomFloat(2, 0.5, 2),
            'stock' => fake()->randomFloat(2, 100, 1000),
            'expiry_date' => fake()->dateTimeBetween('now', '+1 year'),
            'supplier_info' => [
                'name' => fake()->company(),
                'contact' => fake()->phoneNumber(),
                'email' => fake()->companyEmail(),
            ],
            'instructions' => [
                'storage' => fake()->sentence(),
                'handling' => fake()->sentence(),
            ],
        ];
    }

    public function expiringSoon(): self
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => fake()->dateTimeBetween('now', '+7 days'),
        ]);
    }
}
