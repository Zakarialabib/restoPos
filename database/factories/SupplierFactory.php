<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->boolean(90),
            'payment_terms' => [
                'method' => $this->faker->randomElement(['bank_transfer', 'check', 'cash']),
                'days' => $this->faker->randomElement([15, 30, 45, 60]),
                'minimum_order' => $this->faker->numberBetween(100, 1000),
            ],
            'delivery_terms' => [
                'free_shipping_minimum' => $this->faker->numberBetween(500, 2000),
                'delivery_days' => $this->faker->numberBetween(1, 5),
                'shipping_cost' => $this->faker->randomFloat(2, 10, 50),
            ],
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
} 