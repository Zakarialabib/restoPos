<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\KitchenOrderStatus;
use App\Models\KitchenOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitchenOrderFactory extends Factory
{
    protected $model = KitchenOrder::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'assigned_to' => User::factory(),
            'status' => KitchenOrderStatus::Pending,
            'notes' => $this->faker->optional()->sentence(),
            'started_at' => null,
            'completed_at' => null,
            'estimated_preparation_time' => $this->faker->numberBetween(5, 30),
        ];
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => KitchenOrderStatus::Pending,
            'started_at' => null,
            'completed_at' => null,
            'estimated_preparation_time' => $this->faker->numberBetween(5, 30),
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => KitchenOrderStatus::InProgress,
            'started_at' => now(),
            'completed_at' => null,
            'estimated_preparation_time' => $this->faker->numberBetween(5, 30),
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => KitchenOrderStatus::Completed,
            'started_at' => now()->subMinutes(30),
            'completed_at' => now(),
            'estimated_preparation_time' => $this->faker->numberBetween(5, 30),
        ]);
    }

    public function delayed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => KitchenOrderStatus::Delayed,
            'started_at' => now()->subMinutes(45),
            'completed_at' => null,
            'estimated_preparation_time' => $this->faker->numberBetween(5, 30),
        ]);
    }
}
