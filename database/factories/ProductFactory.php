<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'description' => fake()->paragraph(),
            'slug' => Str::slug($name),
            'price' => fake()->randomFloat(2, 5, 100),
            'category_id' => Category::factory(),
            'image' => fake()->imageUrl(640, 480, 'food'),
            'is_available' => fake()->boolean(80), // 80% chance of being available
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
            'stock' => fake()->numberBetween(0, 100),
            'supplier_info' => [
                'name' => fake()->company(),
                'contact' => fake()->phoneNumber(),
                'email' => fake()->companyEmail(),
            ],
            'instructions' => [
                'storage' => fake()->sentence(),
                'handling' => fake()->sentence(),
                'preparation' => fake()->sentence(),
            ],
        ];
    }

    public function available(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
