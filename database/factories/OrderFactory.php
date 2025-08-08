<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'total_amount' => fake()->randomFloat(2, 10, 500),
            'status' => fake()->randomElement(OrderStatus::cases()),
        ];
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::PENDING,
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::COMPLETED,
        ]);
    }
}
