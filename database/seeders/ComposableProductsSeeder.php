<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Composable;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class ComposableProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::with('ingredients')->get();

        // Create composable products for each composable category
        foreach ($categories as $category) {
            if (!$category->is_composable) {
                continue;
            }

            $this->createComposableForCategory($category);
        }
    }

    /**
     * Create composable products for a specific category
     */
    protected function createComposableForCategory(Category $category): void
    {
        $productType = $this->getProductTypeFromCategory($category);
        $ingredients = $category->ingredients;

        if ($ingredients->isEmpty()) {
            return;
        }

        // Create 3-5 composable products per category
        $count = rand(3, 5);
        
        for ($i = 0; $i < $count; $i++) {
            $this->createComposableProduct($category, $productType, $ingredients, $i + 1);
        }
    }

    /**
     * Create a single composable product
     */
    protected function createComposableProduct(Category $category, string $productType, $ingredients, int $index): void
    {
        // Select random ingredients based on product type requirements
        $selectedIngredients = $this->selectIngredientsForProductType($productType, $ingredients);
        
        if ($selectedIngredients->isEmpty()) {
            return;
        }

        $composable = Composable::create([
            'name' => $this->generateProductName($category, $productType, $index),
            'description' => $this->generateDescription($category, $productType),
            'price' => $this->calculateBasePrice($selectedIngredients, $productType),
            'type' => $productType,
            'category_id' => $category->id,
            'configuration_rules' => $this->getConfigurationRules($productType),
            'min_ingredients' => $this->getMinIngredients($productType),
            'max_ingredients' => $this->getMaxIngredients($productType),
            'base_required' => $this->isBaseRequired($productType),
            'base_price' => $this->getBasePrice($productType),
            'status' => true
        ]);

        // Attach ingredients with quantities
        $this->attachIngredientsToComposable($composable, $selectedIngredients, $productType);
    }

    /**
     * Get product type from category
     */
    protected function getProductTypeFromCategory(Category $category): string
    {
        $slug = $category->slug;
        
        return match($slug) {
            'juices', 'juice' => 'juice',
            'smoothies', 'smoothie' => 'smoothie',
            'fruit-bowls', 'fruit_bowls', 'fruit_bowl' => 'fruit_bowl',
            'layered-juices', 'layered_juices' => 'juice',
            'protein-bowls', 'protein_bowls' => 'protein_bowl',
            default => 'juice',
        };
    }

    /**
     * Select ingredients based on product type requirements
     */
    protected function selectIngredientsForProductType(string $productType, $ingredients)
    {
        $productConfig = config("composable.{$productType}");
        
        if (!$productConfig) {
            return $ingredients->take(rand(2, 4));
        }

        $requiredTypes = $productConfig['required_ingredients'] ?? [];
        $optionalTypes = $productConfig['optional_ingredients'] ?? [];
        
        $allTypes = array_merge($requiredTypes, $optionalTypes);
        
        if (empty($allTypes)) {
            return $ingredients->take(rand(2, 4));
        }

        return $ingredients->whereIn('type', $allTypes)->take(rand(2, 5));
    }

    /**
     * Generate product name
     */
    protected function generateProductName(Category $category, string $productType, int $index): string
    {
        $baseName = match($productType) {
            'juice' => 'Fresh Juice',
            'smoothie' => 'Smoothie',
            'fruit_bowl' => 'Fruit Bowl',
            'protein_bowl' => 'Protein Bowl',
            default => 'Custom Product',
        };

        return "{$baseName} #{$index}";
    }

    /**
     * Generate product description
     */
    protected function generateDescription(Category $category, string $productType): string
    {
        return match($productType) {
            'juice' => 'Freshly squeezed juice made with premium ingredients',
            'smoothie' => 'Nutritious smoothie packed with vitamins and minerals',
            'fruit_bowl' => 'Colorful fruit bowl with fresh seasonal fruits',
            'protein_bowl' => 'Protein-rich bowl perfect for post-workout recovery',
            default => 'Custom made product with fresh ingredients',
        };
    }

    /**
     * Calculate base price for the composable
     */
    protected function calculateBasePrice($ingredients, string $productType): float
    {
        $basePrice = $ingredients->sum('price');
        $productConfig = config("composable.{$productType}");
        
        if ($productConfig) {
            $markupPercentage = $productConfig['markup_percentage'] ?? 30;
            $basePrice = $basePrice * (1 + ($markupPercentage / 100));
        }

        return round($basePrice, 2);
    }

    /**
     * Get configuration rules for product type
     */
    protected function getConfigurationRules(string $productType): array
    {
        $productConfig = config("composable.{$productType}");
        
        if (!$productConfig) {
            return ['customizable' => true];
        }

        return [
            'customizable' => true,
            'product_type' => $productType,
            'supports_layers' => isset($productConfig['layers']),
            'markup_percentage' => $productConfig['markup_percentage'] ?? 30,
        ];
    }

    /**
     * Get minimum ingredients for product type
     */
    protected function getMinIngredients(string $productType): int
    {
        $workflowSteps = config("composable.workflow_steps.{$productType}", []);
        return $workflowSteps['ingredients']['min'] ?? 1;
    }

    /**
     * Get maximum ingredients for product type
     */
    protected function getMaxIngredients(string $productType): int
    {
        $workflowSteps = config("composable.workflow_steps.{$productType}", []);
        return $workflowSteps['ingredients']['max'] ?? 5;
    }

    /**
     * Check if base is required for product type
     */
    protected function isBaseRequired(string $productType): bool
    {
        $workflowSteps = config("composable.workflow_steps.{$productType}", []);
        return $workflowSteps['base']['required'] ?? false;
    }

    /**
     * Get base price for product type
     */
    protected function getBasePrice(string $productType): float
    {
        $productConfig = config("composable.{$productType}");
        return $productConfig['sizes']['medium']['base_price'] ?? 20.00;
    }

    /**
     * Attach ingredients to composable with appropriate quantities
     */
    protected function attachIngredientsToComposable(Composable $composable, $ingredients, string $productType): void
    {
        $attachData = [];
        
        foreach ($ingredients as $ingredient) {
            $quantity = $this->calculateIngredientQuantity($ingredient, $productType);
            $attachData[$ingredient->id] = [
                'quantity' => $quantity,
                'unit' => $ingredient->unit ?? 'ml',
                'is_base' => $ingredient->type->value === 'base',
            ];
        }

        $composable->ingredients()->attach($attachData);
    }

    /**
     * Calculate ingredient quantity based on product type
     */
    protected function calculateIngredientQuantity($ingredient, string $productType): float
    {
        $baseQuantity = match($ingredient->type->value) {
            'base' => 100.0,
            'fruit' => 50.0,
            'vegetable' => 30.0,
            'protein' => 20.0,
            'condiment' => 10.0,
            'sugar' => 5.0,
            default => 25.0,
        };

        // Adjust based on product type
        $multiplier = match($productType) {
            'juice' => 1.0,
            'smoothie' => 1.2,
            'fruit_bowl' => 0.8,
            'protein_bowl' => 1.5,
            default => 1.0,
        };

        return round($baseQuantity * $multiplier, 2);
    }
}
