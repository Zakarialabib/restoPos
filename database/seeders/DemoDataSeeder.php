<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating categories...');
        $categories = $this->createCategories();

        $this->command->info('Creating ingredients...');
        $ingredients = $this->createIngredients();

        $this->command->info('Creating products...');
        $this->createProducts($categories, $ingredients);
    }

    protected function createCategories(): array
    {
        return [
            'fruits' => Category::factory()->create([
                'name' => 'Fruits',
                'description' => 'Fresh fruits for juices and smoothies',
                'image' => 'categories/fruits.jpg',
            ]),
            'juices' => Category::factory()->create([
                'name' => 'Fresh Juices',
                'description' => 'Freshly squeezed juices made from seasonal fruits',
                'image' => 'categories/juices.jpg',
            ]),
            'smoothies' => Category::factory()->create([
                'name' => 'Healthy Smoothies',
                'description' => 'Nutritious smoothies packed with superfoods',
                'image' => 'categories/smoothies.jpg',
            ]),
            'salads' => Category::factory()->create([
                'name' => 'Fresh Salads',
                'description' => 'Custom salads with fresh ingredients',
                'image' => 'categories/salads.jpg',
            ]),
            'bowls' => Category::factory()->create([
                'name' => 'Power Bowls',
                'description' => 'Nutrient-rich bowls for a healthy meal',
                'image' => 'categories/bowls.jpg',
            ]),
            'desserts' => Category::factory()->create([
                'name' => 'Desserts',
                'description' => 'Sweet treats to satisfy your sweet tooth',
                'image' => 'categories/desserts.jpg',
            ]),
        ];
    }

    protected function createIngredients(): array
    {
        $ingredients = [
            'fruits' => Ingredient::factory()
                ->count(10)
                ->fruit()
                ->sequence(fn ($sequence) => ['name' => "Fresh {$sequence->index}"])
                ->create(),

            'vegetables' => Ingredient::factory()
                ->count(10)
                ->vegetable()
                ->sequence(fn ($sequence) => ['name' => "Fresh {$sequence->index}"])
                ->create(),

            'supplements' => Ingredient::factory()
                ->count(5)
                ->supplement()
                ->expensive()
                ->sequence(fn ($sequence) => ['name' => "Premium {$sequence->index}"])
                ->create(),
        ];

        // Create seasonal ingredients
        Ingredient::factory()
            ->count(4)
            ->seasonal()
            ->create();

        // Create ingredients with various stock levels
        Ingredient::factory()
            ->count(3)
            ->fruit()
            ->lowStock()
            ->create();

        Ingredient::factory()
            ->count(2)
            ->vegetable()
            ->outOfStock()
            ->create();

        Ingredient::factory()
            ->count(5)
            ->supplement()
            ->withHighStock()
            ->create();

        return $ingredients;
    }

    protected function createProducts(array $categories, array $ingredients): void
    {
        // Create regular juices
        Product::factory()
            ->count(5)
            ->for($categories['juices'])
            ->withPrice()
            ->withIngredients(3)
            ->withRecipe()
            ->create();

        // Create featured smoothies
        Product::factory()
            ->count(3)
            ->featured()
            ->for($categories['smoothies'])
            ->withPrice()
            ->withIngredients(4)
            ->withRecipe()
            ->create();

        // Create composable salads
        Product::factory()
            ->count(4)
            ->composable()
            ->for($categories['salads'])
            ->withPrice()
            ->withIngredients(5)
            ->withRecipe()
            ->create();

        // Create seasonal products
        Product::factory()
            ->count(4)
            ->seasonal()
            ->withPrice()
            ->withIngredients(3)
            ->withRecipe()
            ->create();

        // Create products with stock issues
        Product::factory()
            ->count(2)
            ->unavailable()
            ->lowStock()
            ->for($categories['juices'])
            ->withPrice()
            ->withIngredients(3)
            ->withRecipe()
            ->create();

        // Create power bowls
        Product::factory()
            ->count(3)
            ->for($categories['bowls'])
            ->withPrice()
            ->withIngredients(6)
            ->withRecipe()
            ->create();
    }
}
