<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Composable;
use App\Models\Ingredient;
use App\Models\StockLog;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComposableProductService
{
  
    public function createComposableProduct(array $data): Composable
    {
        try {
            return DB::transaction(function () use ($data) {
                if (! $this->validateIngredientCompatibility($data['ingredients'])) {
                    throw new Exception('Invalid ingredient combination');
                }

                $composable = Composable::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'base_price' => $data['base_price'],
                    'status' => $data['status'] ?? true,
                    'category_id' => $data['category_id'],
                    'preparation_instructions' => $data['preparation_instructions'] ?? [],
                    'preparation_time' => $data['preparation_time'] ?? 0,
                    'portion_size' => $data['portion_size'],
                    'portion_unit' => $data['portion_unit'],
                    'allergens' => $this->extractAllergens($data['ingredients']),
                ]);

                $this->attachIngredients($composable, $data['ingredients']);
                $this->updateInventoryLevels($composable, $data['ingredients']);

                Cache::tags(['composables'])->flush();

                return $composable;
            });
        } catch (Exception $e) {
            Log::error('Failed to create composable product', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function validateIngredientAvailability(array $ingredients): bool
    {
        $ingredientIds = array_keys($ingredients);
        $availableIngredients = Ingredient::whereIn('id', $ingredientIds)
            ->select('id', 'stock_quantity')
            ->get()
            ->keyBy('id');

        foreach ($ingredients as $ingredientId => $quantity) {
            $ingredient = $availableIngredients->get($ingredientId);
            if (! $ingredient || $ingredient->stock_quantity < $quantity) {
                return false;
            }
        }
        return true;
    }

    public function calculatePortions(array $ingredients, string $size = 'medium'): array
    {
        $sizeMultipliers = [
            'small' => 0.8,
            'medium' => 1.0,
            'large' => 1.2,
        ];

        $multiplier = $sizeMultipliers[$size] ?? 1.0;
        $totalIngredients = count($ingredients);
        $basePortionSize = 100 / $totalIngredients;

        return collect($ingredients)->mapWithKeys(fn($ingredient, $id) => [$id => ceil($basePortionSize * $multiplier)])->toArray();
    }

    public function getPopularCombinations(int $limit = 10): Collection
    {
        return Cache::remember('popular_combinations', now()->addHour(), fn() => DB::table('order_items')
            ->select('ingredients', DB::raw('COUNT(*) as frequency'))
            ->where('is_composable', true)
            ->groupBy('ingredients')
            ->orderByDesc('frequency')
            ->limit($limit)
            ->get());
    }

    public function suggestComplementaryIngredients(array $selectedIngredients): Collection
    {
        return Cache::remember(
            'complementary_ingredients_' . md5(json_encode($selectedIngredients)),
            now()->addHour(),
            fn() => $this->analyzeComplementaryIngredients($selectedIngredients)
        );
    }

    public function extractAllergens(array $ingredients): array
    {
        $allergens = [];

        foreach ($ingredients as $ingredientId => $quantity) {
            $ingredient = Ingredient::find($ingredientId);
            if ($ingredient && $ingredient->allergens) {
                $allergens = array_merge($allergens, $ingredient->allergens);
            }
        }

        return array_unique($allergens);
    }

    public function validateLayeredJuice(array $layers): bool
    {
        $config = config('composable.juice.layers');

        // Check total layers
        if (count($layers) < $config['min_layers'] || count($layers) > $config['max_layers']) {
            return false;
        }

        // Check layer type limits
        $layerCounts = collect($layers)->groupBy('type')->map->count();

        foreach ($config['layer_types'] as $type => $settings) {
            if (($layerCounts[$type] ?? 0) > $settings['max_per_drink']) {
                return false;
            }
        }

        // Validate ingredient compatibility
        return $this->validateIngredientCompatibility(
            collect($layers)->pluck('ingredient_id')->toArray()
        );
    }

    public function calculateLayerDistribution(int $totalLayers, string $size = 'medium'): array
    {
        $config = config('composable.juice');
        $totalVolume = (int) str_replace('ml', '', $config['sizes'][$size]['capacity']);
        $volumePerLayer = $totalVolume / $totalLayers;

        return array_fill(0, $totalLayers, [
            'volume' => $volumePerLayer,
            'percentage' => (100 / $totalLayers)
        ]);
    }

    public function calculateLayeredJuiceCost(array $layers): float
    {
        $totalCost = 0;
        $config = config('composable.juice');

        foreach ($layers as $layer) {
            $ingredient = Ingredient::find($layer['ingredient_id']);
            if (! $ingredient) {
                continue;
            }

            // Get the layer volume based on position and total layers
            $layerVolume = $this->calculateLayerVolume(
                $layer['position'],
                count($layers),
                $layer['size'] ?? 'medium'
            );

            // Get yield percentage from config or default
            $yieldPercentage = $config['default_yields'][$ingredient->name] ?? $config['default_yields']['default'];

            // Calculate raw amount needed based on yield
            $rawAmount = $layerVolume / $yieldPercentage;

            // Get layer type multiplier for processing costs
            $typeMultiplier = $config['layers']['layer_types'][$layer['type']]['yield_multiplier'] ?? 1.0;

            // Calculate base cost
            $cost = $rawAmount * $ingredient->cost * $typeMultiplier;

            $totalCost += $cost;
        }

        // Apply markup
        $markup = 1 + ($config['markup_percentage'] / 100);
        return $totalCost * $markup;
    }

    // public function calculateLayeredJuiceCost(array $layers): float
    // {
    //     $totalCost = 0;
    //     $config = config('composable.juice');

    //     foreach ($layers as $layer) {
    //         $ingredient = Ingredient::find($layer['ingredient_id']);
    //         if ( ! $ingredient) {
    //             continue;
    //         }

    //         // Calculate volume based on layer position and total layers
    //         $layerVolume = $this->calculateLayerVolume(
    //             $layer['position'],
    //             count($layers),
    //             $layer['size'] ?? 'medium'
    //         );

    //         // Calculate cost based on yield and volume
    //         $rawAmount = $layerVolume / ($ingredient->yield_percentage / 100);
    //         $cost = $rawAmount * $ingredient->price_per_unit;

    //         // Add any additional costs (syrups, toppings)
    //         if ('syrup' === $layer['type']) {
    //             $cost *= 1.2; // 20% extra for syrup processing
    //         } elseif ('yogurt' === $layer['type']) {
    //             $cost *= 1.1; // 10% extra for yogurt processing
    //         }

    //         $totalCost += $cost;
    //     }

    //     return $totalCost;
    // }

    // protected function calculateLayerVolume(int $position, int $totalLayers, string $size = 'medium'): float
    // {
    //     $sizeCapacities = [
    //         'small' => 250,
    //         'medium' => 350,
    //         'large' => 500
    //     ];

    //     $totalVolume = $sizeCapacities[$size] ?? 350;
    //     return $totalVolume / $totalLayers;
    // }

    public function suggestLayerCombinations(array $selectedIngredients = []): array
    {
        $popularCombos = $this->getPopularCombinations(5)
            ->map(fn($combo) => [
                'ingredients' => json_decode($combo->ingredients, true),
                'frequency' => $combo->frequency
            ])
            ->toArray();

        $complementary = [];
        if (! empty($selectedIngredients)) {
            $complementary = $this->suggestComplementaryIngredients($selectedIngredients)
                ->map(fn($frequency, $ingredientId) => [
                    'ingredient_id' => $ingredientId,
                    'frequency' => $frequency
                ])
                ->toArray();
        }

        return [
            'popular_combinations' => $popularCombos,
            'complementary_ingredients' => $complementary
        ];
    }

    protected function attachIngredients(Composable $composable, array $ingredients): void
    {
        $attachData = collect($ingredients)
            ->mapWithKeys(fn($quantity, $ingredientId) => [$ingredientId => ['quantity' => $quantity]])
            ->toArray();

        $composable->ingredients()->attach($attachData);

        // Eager load ingredients after attaching
        $composable->load('ingredients');
    }

    protected function updateInventoryLevels(Composable $composable, array $ingredients): void
    {
        try {
            DB::beginTransaction();

            $ingredientModels = Ingredient::whereIn('id', array_keys($ingredients))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validate all ingredients first
            foreach ($ingredients as $ingredientId => $quantity) {
                $ingredient = $ingredientModels->get($ingredientId);
                if (! $ingredient) {
                    throw new Exception(
                        "Ingredient not found: {$ingredientId}"
                    );
                }

                if ($ingredient->stock_quantity < $quantity) {
                    throw new Exception(
                        "Insufficient stock for ingredient: {$ingredient->name}"
                    );
                }
            }

            // Batch update stock levels
            $updates = [];
            foreach ($ingredients as $ingredientId => $quantity) {
                $ingredient = $ingredientModels->get($ingredientId);
                $updates[] = [
                    'id' => $ingredientId,
                    'stock_quantity' => DB::raw("stock_quantity - {$quantity}"),
                    'updated_at' => now()
                ];

                // Create stock log
                StockLog::create([
                    'stockable_type' => Ingredient::class,
                    'stockable_id' => $ingredientId,
                    'quantity' => -$quantity,
                    'reason' => 'composable_creation',
                    'reference_id' => $composable->id,
                    'reference_type' => Composable::class
                ]);
            }

            // Perform batch update
            if (! empty($updates)) {
                Ingredient::upsert($updates, ['id'], ['stock_quantity', 'updated_at']);
            }

            DB::commit();
            Cache::tags(['ingredients', 'stock'])->flush();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update inventory levels', [
                'error' => $e->getMessage(),
                'composable_id' => $composable->id,
                'ingredients' => $ingredients
            ]);
            throw $e;
        }
    }

    protected function calculateWasteCost(array $ingredients, float $quantity): float
    {
        return collect($ingredients)->sum(function ($ingredientQuantity, $ingredientId) use ($quantity) {
            $ingredient = Ingredient::find($ingredientId);
            if (! $ingredient) {
                return 0;
            }

            return $ingredient->cost * ($ingredientQuantity * $quantity / 100);
        });
    }

    protected function analyzeComplementaryIngredients(array $selectedIngredients): Collection
    {
        return DB::table('order_items')
            ->select('ingredients')
            ->whereJsonContains('ingredients', $selectedIngredients)
            ->get()
            ->pluck('ingredients')
            ->flatten()
            ->diff($selectedIngredients)
            ->countBy()
            ->sortDesc()
            ->take(5);
    }

    protected function validateIngredientCompatibility(array $ingredients): bool
    {
        $ingredientIds = array_keys($ingredients);
        $ingredientTypes = Ingredient::whereIn('id', $ingredientIds)
            ->pluck('type', 'id')
            ->toArray();

        // Add your compatibility rules here
        // For example, prevent mixing certain ingredient types
        $incompatibleTypes = [
            'dairy' => ['citrus'],
            'protein' => ['acid'],
        ];

        foreach ($ingredientTypes as $id => $type) {
            foreach ($ingredientTypes as $otherId => $otherType) {
                if (
                    $id !== $otherId &&
                    isset($incompatibleTypes[$type]) &&
                    in_array($otherType, $incompatibleTypes[$type])
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function calculateLayerVolume(int $position, int $totalLayers, string $size = 'medium'): float
    {
        $config = config('composable.juice');
        $sizeCapacities = [
            'small' => 250,
            'medium' => 350,
            'large' => 500
        ];

        $totalVolume = (int) str_replace('ml', '', $config['sizes'][$size]['capacity']);
        return $totalVolume / $totalLayers;
    }
}
