<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Support\Collection;

class AIRecipeSuggestionService
{
    public function suggestNewRecipes(int $limit = 3): Collection
    {
        $popularIngredients = $this->getPopularIngredients();
        $profitableIngredients = $this->getProfitableIngredients();

        $suggestedRecipes = collect();

        for ($i = 0; $i < $limit; $i++) {
            $recipe = [
                'name' => 'AI Suggested Recipe ' . ($i + 1),
                'ingredients' => $this->combineIngredients($popularIngredients, $profitableIngredients),
            ];
            $suggestedRecipes->push($recipe);
        }

        return $suggestedRecipes;
    }

    private function getPopularIngredients(): Collection
    {
        return Ingredient::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(10)
            ->get();
    }

    private function getProfitableIngredients(): Collection
    {
        return Ingredient::orderByDesc('cost_per_unit')
            ->limit(10)
            ->get();
    }

    private function combineIngredients(Collection $popular, Collection $profitable): Collection
    {
        return $popular->random(2)->merge($profitable->random(2))->unique();
    }
}