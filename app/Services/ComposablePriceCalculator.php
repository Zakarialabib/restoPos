<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\IngredientType;
use App\Models\ComposableConfiguration;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class ComposablePriceCalculator
{
    /**
     * Calculate the price for a composable product based on selections and configuration.
     *
     * @param array<int, string> $selectedIngredientIds IDs of the main ingredients (e.g., fruits).
     * @param string|null $selectedBaseId ID of the selected base ingredient, if applicable.
     * @param array<int, string> $selectedAddonIds IDs of selected addon ingredients.
     * @param string $selectedSizeIdentifier Identifier for the selected size (e.g., 'small', 'medium').
     * @param string|null $selectedSugarId ID of the selected sugar ingredient, if applicable.
     * @param ComposableConfiguration $configuration The configuration rules for this composable type.
     * @return float The calculated price.
     * @throws InvalidArgumentException If selections are invalid or required ingredients are not found.
     */
    public function calculate(
        array $selectedIngredientIds,
        ?string $selectedBaseId,
        array $selectedAddonIds,
        string $selectedSizeIdentifier,
        ?string $selectedSugarId,
        ComposableConfiguration $configuration
    ): float {
        if (empty($selectedIngredientIds) || empty($selectedSizeIdentifier)) {
            Log::warning('ComposablePriceCalculator: Missing required ingredients or size identifier.', [
                'ingredients' => $selectedIngredientIds,
                'size' => $selectedSizeIdentifier,
            ]);
            return 0.0; // Or throw exception?
        }

        // Combine all ingredient IDs for efficient fetching
        $allIngredientIds = array_filter(array_merge(
            $selectedIngredientIds,
            [$selectedBaseId],
            $selectedAddonIds,
            [$selectedSugarId]
        ));

        if (empty($allIngredientIds)) {
            Log::warning('ComposablePriceCalculator: No valid ingredient IDs provided.');
            return 0.0;
        }

        $cacheKey = $this->generateCacheKey($selectedIngredientIds, $selectedBaseId, $selectedAddonIds, $selectedSizeIdentifier, $selectedSugarId);

        try {
            return Cache::remember($cacheKey, now()->addMinutes(15), function () use (
                $selectedIngredientIds,
                $selectedBaseId,
                $selectedAddonIds,
                $selectedSizeIdentifier,
                $selectedSugarId,
                $configuration,
                $allIngredientIds // Pass fetched ingredients to closure
            ) {
                // Fetch all relevant ingredients at once
                $ingredients = Ingredient::whereIn('id', $allIngredientIds)
                    // Eager load price relationship if managed by HasPricing trait / Price model
                    // ->with('currentPrice') // Example if relationship exists
                    ->get()
                    ->keyBy('id');

                // --- Validation ---
                // Ensure all requested IDs were found
                if ($ingredients->count() !== count(array_unique($allIngredientIds))) {
                    $missing = array_diff(array_unique($allIngredientIds), $ingredients->keys()->all());
                    throw new InvalidArgumentException('One or more selected ingredients not found: ' . implode(', ', $missing));
                }
                // Ensure base is valid if selected
                if ($selectedBaseId && ( ! isset($ingredients[$selectedBaseId]) || $ingredients[$selectedBaseId]->type !== IngredientType::BASE)) {
                    // throw new InvalidArgumentException("Invalid base selection: {$selectedBaseId}");
                }
                // Ensure sugar is valid if selected
                if ($selectedSugarId && ( ! isset($ingredients[$selectedSugarId]) || $ingredients[$selectedSugarId]->type !== IngredientType::SUGAR)) {
                    // throw new InvalidArgumentException("Invalid sugar selection: {$selectedSugarId}");
                }
                 // Ensure addons are valid
                foreach ($selectedAddonIds as $addonId) {
                    if ( ! isset($ingredients[$addonId])) { // Basic check, could add type check if needed
                         throw new InvalidArgumentException("Invalid addon selection: {$addonId}");
                    }
                }


                // --- Price Calculation ---
                $totalPrice = 0.0;

                // 1. Sum price of main ingredients
                foreach ($selectedIngredientIds as $id) {
                    $ingredient = $ingredients->get($id);
                    if ( ! $ingredient) continue; // Should have been caught by validation above
                     // Assuming $ingredient->price gives the current selling price via HasPricing trait/accessor
                    $totalPrice += (float) ($ingredient->price ?? 0.0);
                }

                // 2. Add price of base (if selected and configuration requires it)
                if ($configuration->has_base && $selectedBaseId && isset($ingredients[$selectedBaseId])) {
                    $totalPrice += (float) ($ingredients[$selectedBaseId]->price ?? 0.0);
                }

                // 3. Add price of sugar (if selected and configuration requires it)
                 // Handle 'none' case implicitly - if $selectedSugarId is null or refers to an ingredient with price 0
                if ($configuration->has_sugar && $selectedSugarId && isset($ingredients[$selectedSugarId])) {
                    $totalPrice += (float) ($ingredients[$selectedSugarId]->price ?? 0.0);
                }

                // 4. Add price of addons (if configuration allows)
                if ($configuration->has_addons) {
                    foreach ($selectedAddonIds as $id) {
                        if (isset($ingredients[$id])) {
                            $totalPrice += (float) ($ingredients[$id]->price ?? 0.0);
                        }
                    }
                }


                // 5. Apply size multiplier (if configuration requires it)
                $sizeMultiplier = 1.0;
                if ($configuration->has_size) {
                    $sizeMultiplier = $configuration->getSizeMultiplier($selectedSizeIdentifier);
                    if ($sizeMultiplier <= 0) {
                        Log::warning('ComposablePriceCalculator: Invalid size multiplier found.', ['size' => $selectedSizeIdentifier, 'multiplier' => $sizeMultiplier]);
                        $sizeMultiplier = 1.0; // Default to 1x if multiplier is invalid
                    }
                }

                $finalPrice = $totalPrice * $sizeMultiplier;

                return round($finalPrice, 2);
            });
        } catch (Throwable $e) {
            // Log the detailed error and rethrow or return a default
            Log::error('ComposablePriceCalculator::calculate Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ingredients' => $selectedIngredientIds,
                'base' => $selectedBaseId,
                'addons' => $selectedAddonIds,
                'size' => $selectedSizeIdentifier,
                'sugar' => $selectedSugarId,
            ]);
            // Rethrow the original exception to signal failure
            throw $e;
            // Or return 0.0 / default price? Depends on desired behaviour on error
            // return 0.0;
        }
    }

    private function generateCacheKey(array $ingredientIds, ?string $baseId, array $addonIds, string $size, ?string $sugarId): string
    {
        // Sort IDs for consistency
        sort($ingredientIds);
        sort($addonIds);
        $allIds = array_filter(array_merge($ingredientIds, [$baseId], $addonIds, [$sugarId]));
        $keyPart = implode('_', $allIds);
        return "price_calc_v2_{$keyPart}_{$size}"; // Added v2 to invalidate old cache
    }
}
