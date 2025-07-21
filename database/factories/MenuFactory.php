<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => $name,
            'label' => Str::title($name),
            'url' => '/' . Str::slug($name),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['home', 'info', 'menu', 'person', 'settings']),
            'status' => true,
            'parent_id' => null,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'new_window' => false,
            'placement' => 'header',
            'type' => 'link',
        ];
    }

    public function header(): self
    {
        return $this->state([
            'placement' => 'header'
        ]);
    }

    public function footer(): self
    {
        return $this->state([
            'placement' => 'footer'
        ]);
    }

    public function inactive(): self
    {
        return $this->state([
            'status' => false
        ]);
    }

    public function newWindow(): self
    {
        return $this->state([
            'new_window' => true
        ]);
    }

    public function asChild(Menu $parent): self
    {
        return $this->state([
            'parent_id' => $parent->id,
        ]);
    }
}
