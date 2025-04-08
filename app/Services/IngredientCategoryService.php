<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CategoryType;
use App\Enums\ItemType;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class IngredientCategoryService
{
    public function getCategoriesByType(ItemType $type): Collection
    {
        return Cache::remember("categories_{$type->value}", 3600, fn () => Category::where('type', $type)
            ->orderBy('name')
            ->get());
    }

    public function getIngredientsByCategory(Category $category): Collection
    {
        return $category->ingredients()
            ->with(['prices' => function ($query): void {
                $query->where('is_current', true);
            }])
            ->get();
    }

    public function validateCategoryAssignment(Ingredient $ingredient, Category $category): bool
    {
        return match($category->type) {
            CategoryType::INGREDIENT,
            CategoryType::BASE,
            CategoryType::FRUIT => true,
            default => false
        };
    }

    public function reassignCategory(Ingredient $ingredient, Category $newCategory): void
    {
        if ( ! $this->validateCategoryAssignment($ingredient, $newCategory)) {
            throw new InvalidArgumentException('Invalid category type for ingredient');
        }

        $ingredient->update(['category_id' => $newCategory->id]);
    }

    public function getComposableCategories(): Collection
    {
        return Cache::remember('composable_categories', 3600, fn () => Category::query()
            ->where('is_composable', true)
            ->where('status', true)
            ->with(['composableConfiguration'])
            ->get());
    }

    public function getIngredientsForComposable(Category $category, ?string $type = null): Collection
    {
        $query = Ingredient::query()
            ->where('status', true)
            ->where(function ($query) use ($category, $type): void {
                $query->where('category_id', $category->id);

                if ($type) {
                    $query->where('type', $type);
                }
            })
            ->with(['prices', 'category']);

        return $query->get();
    }
}
