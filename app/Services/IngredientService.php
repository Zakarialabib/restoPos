<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IngredientService
{
    public function createIngredient(array $ingredientData): Ingredient
    {
        return DB::transaction(function () use ($ingredientData) {
            $ingredient = Ingredient::create([
                'name' => $ingredientData['name'],
                'description' => $ingredientData['description'],
                'slug' => Str::slug($ingredientData['name']),
                'category_id' => $ingredientData['category_id'],
                'status' => $ingredientData['status'],
                'stock_quantity' => $ingredientData['stock'],
                'reorder_point' => $ingredientData['reorder_point'],
                'unit' => $ingredientData['unit'],
                'cost' => $ingredientData['cost'],
                'expiry_date' => $ingredientData['expiry_date'] ?? null,
                'storage_location' => $ingredientData['storage_location'] ?? null,
                'supplier_info' => $ingredientData['supplier_info'] ?? null,
                'image' => $ingredientData['image'] ?? null,
                'is_seasonal' => $ingredientData['is_seasonal'] ?? false,
                'is_composable' => $ingredientData['is_composable'] ?? false,
                'allergens' => $ingredientData['allergens'] ?? [],
                'nutritional_info' => $ingredientData['nutritional_info'] ?? [],
                'preparation_instructions' => $ingredientData['preparation_instructions'] ?? [],
                'minimum_order_quantity' => $ingredientData['minimum_order_quantity'] ?? 0,
                'lead_time_days' => $ingredientData['lead_time_days'] ?? 0,
            ]);

            // Update cost history
            if (isset($ingredientData['cost']) && $ingredientData['cost'] > 0) {
                $this->addPrice($ingredient->id, $ingredientData['cost'], 0, 'Initial cost setup');
            }

            return $ingredient->fresh();
        });
    }

    public function updateIngredient(Ingredient $ingredient, array $ingredientData): Ingredient
    {
        return DB::transaction(function () use ($ingredient, $ingredientData) {
            $ingredient->update([
                'name' => $ingredientData['name'],
                'description' => $ingredientData['description'],
                'slug' => Str::slug($ingredientData['name']),
                'category_id' => $ingredientData['category_id'],
                'status' => $ingredientData['status'],
                'stock_quantity' => $ingredientData['stock'],
                'reorder_point' => $ingredientData['reorder_point'],
                'unit' => $ingredientData['unit'],
                'expiry_date' => $ingredientData['expiry_date'] ?? null,
                'storage_location' => $ingredientData['storage_location'] ?? null,
                'supplier_info' => $ingredientData['supplier_info'] ?? null,
                'is_seasonal' => $ingredientData['is_seasonal'] ?? false,
                'is_composable' => $ingredientData['is_composable'] ?? false,
                'allergens' => $ingredientData['allergens'] ?? [],
                'nutritional_info' => $ingredientData['nutritional_info'] ?? [],
                'preparation_instructions' => $ingredientData['preparation_instructions'] ?? [],
                'minimum_order_quantity' => $ingredientData['minimum_order_quantity'] ?? 0,
                'lead_time_days' => $ingredientData['lead_time_days'] ?? 0,
            ]);

            // Update image if provided
            if (isset($ingredientData['image']) && !is_string($ingredientData['image'])) {
                $ingredient->update(['image' => $ingredientData['image']]);
            }

            // Update cost if provided
            if (isset($ingredientData['cost']) && $ingredientData['cost'] > 0) {
                $this->addPrice($ingredient->id, $ingredientData['cost'], 0, 'Cost update');
            }

            return $ingredient->fresh();
        });
    }

    public function bulkUpdateCategory(array $ingredientIds, $categoryId): void
    {
        DB::transaction(function () use ($ingredientIds, $categoryId) {
            Ingredient::whereIn('id', $ingredientIds)
                ->update(['category_id' => $categoryId]);
        });
    }

    public function bulkUpdateStatus(array $ingredientIds, bool $status): void
    {
        DB::transaction(function () use ($ingredientIds, $status) {
            Ingredient::whereIn('id', $ingredientIds)
                ->update(['status' => $status]);
        });
    }

    public function bulkDelete(array $ingredientIds): void
    {
        DB::transaction(function () use ($ingredientIds) {
            Ingredient::whereIn('id', $ingredientIds)->delete();
        });
    }

    public function getPriceHistory(int $ingredientId): Collection
    {
        $ingredient = Ingredient::findOrFail($ingredientId);
        return $ingredient->prices()
            ->orderByDesc('effective_date')
            ->get()
            ->map(function ($price) {
                return [
                    'cost' => $price->cost,
                    'price' => $price->price,
                    'previous_cost' => $price->previous_cost,
                    'previous_price' => $price->previous_price,
                    'change' => $price->cost_change,
                    'change_percentage' => $price->cost_change_percentage,
                    'date' => $price->effective_date->format('Y-m-d'),
                    'reason' => $price->reason,
                ];
            });
    }

    public function addPrice(int $ingredientId, float $cost, float $price, ?string $notes = null): void
    {
        $ingredient = Ingredient::findOrFail($ingredientId);
        
        DB::transaction(function () use ($ingredient, $cost, $price, $notes) {
            // Deactivate current price
            $ingredient->prices()->where('is_current', true)->update(['is_current' => false]);

            // Create new price record
            $ingredient->prices()->create([
                'cost' => $cost,
                'price' => $price,
                'previous_cost' => $ingredient->cost,
                'previous_price' => $ingredient->price,
                'effective_date' => now(),
                'is_current' => true,
                'reason' => $notes,
            ]);

            // Update base cost and price
            $ingredient->update([
                'cost' => $cost,
                'price' => $price,
            ]);
        });
    }

    public function deleteIngredient(Ingredient $ingredient): void
    {
        if ($ingredient->products()->exists()) {
            throw new \InvalidArgumentException("Cannot delete ingredient used in products");
        }

        DB::transaction(function () use ($ingredient) {
            $ingredient->prices()->delete();
            $ingredient->stockLogs()->delete();
            $ingredient->delete();
        });
    }

    public function toggleStatus(Ingredient $ingredient): void
    {
        $ingredient->status = !$ingredient->status;
        $ingredient->save();
    }

    public function getIngredientAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_ingredients' => Ingredient::count(),
            'active_ingredients' => Ingredient::where('status', true)->count(),
            'low_stock_count' => Ingredient::lowStock()->count(),
            'out_of_stock_count' => Ingredient::outOfStock()->count(),
            'expiring_soon' => $this->getExpiringSoonIngredients(),
            'usage_trends' => $this->getUsageTrends($startDate, $endDate),
            'cost_trends' => $this->getCostTrends($startDate, $endDate),
            'wastage_stats' => $this->getWastageStats($startDate, $endDate),
        ];
    }

    protected function getExpiringSoonIngredients(): Collection
    {
        return Ingredient::query()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date')
            ->get()
            ->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'expiry_date' => $ingredient->expiry_date,
                    'days_until_expiry' => now()->diffInDays($ingredient->expiry_date),
                    'stock_quantity' => $ingredient->stock_quantity,
                ];
            });
    }

    protected function getUsageTrends(Carbon $startDate, Carbon $endDate): array
    {
        $usageData = DB::table('stock_logs')
            ->join('ingredients', 'ingredients.id', '=', 'stock_logs.stockable_id')
            ->where('stock_logs.stockable_type', Ingredient::class)
            ->whereBetween('stock_logs.created_at', [$startDate, $endDate])
            ->select(
                'ingredients.id',
                'ingredients.name',
                DB::raw('SUM(CASE WHEN stock_logs.type = "reduction" THEN ABS(stock_logs.quantity) ELSE 0 END) as total_usage'),
                DB::raw('COUNT(DISTINCT CASE WHEN stock_logs.type = "reduction" THEN DATE(stock_logs.created_at) END) as usage_days')
            )
            ->groupBy('ingredients.id', 'ingredients.name')
            ->orderByDesc('total_usage')
            ->get();

        return [
            'most_used' => $usageData->take(5),
            'total_usage' => $usageData->sum('total_usage'),
            'average_daily_usage' => $usageData->avg('total_usage') / max(1, $startDate->diffInDays($endDate)),
        ];
    }

    protected function getCostTrends(Carbon $startDate, Carbon $endDate): array
    {
        $costData = DB::table('prices')
            ->join('ingredients', 'ingredients.id', '=', 'prices.priceable_id')
            ->where('prices.priceable_type', Ingredient::class)
            ->whereBetween('prices.entry_date', [$startDate, $endDate])
            ->select(
                'ingredients.id',
                'ingredients.name',
                DB::raw('AVG(prices.cost) as average_cost'),
                DB::raw('MIN(prices.cost) as min_cost'),
                DB::raw('MAX(prices.cost) as max_cost'),
                DB::raw('FIRST_VALUE(prices.cost) OVER (PARTITION BY ingredients.id ORDER BY prices.entry_date) as start_cost'),
                DB::raw('LAST_VALUE(prices.cost) OVER (PARTITION BY ingredients.id ORDER BY prices.entry_date) as end_cost')
            )
            ->groupBy('ingredients.id', 'ingredients.name')
            ->get();

        return [
            'cost_changes' => $costData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'cost_change' => $item->end_cost - $item->start_cost,
                    'cost_change_percentage' => $item->start_cost > 0 
                        ? (($item->end_cost - $item->start_cost) / $item->start_cost) * 100 
                        : 0,
                    'average_cost' => $item->average_cost,
                    'volatility' => $item->max_cost - $item->min_cost,
                ];
            }),
            'total_cost_increase' => $costData->sum(fn($item) => max(0, $item->end_cost - $item->start_cost)),
            'total_cost_decrease' => $costData->sum(fn($item) => max(0, $item->start_cost - $item->end_cost)),
            'average_cost_change_percentage' => $costData->avg(fn($item) => 
                $item->start_cost > 0 
                    ? (($item->end_cost - $item->start_cost) / $item->start_cost) * 100 
                    : 0
            ),
        ];
    }

    protected function getWastageStats(Carbon $startDate, Carbon $endDate): array
    {
        $wastageData = DB::table('stock_logs')
            ->join('ingredients', 'ingredients.id', '=', 'stock_logs.stockable_id')
            ->where('stock_logs.stockable_type', Ingredient::class)
            ->where('stock_logs.type', 'reduction')
            ->where('stock_logs.reason', 'like', '%waste%')
            ->whereBetween('stock_logs.created_at', [$startDate, $endDate])
            ->select(
                'ingredients.id',
                'ingredients.name',
                DB::raw('SUM(ABS(stock_logs.quantity)) as total_wastage'),
                DB::raw('COUNT(DISTINCT DATE(stock_logs.created_at)) as wastage_days')
            )
            ->groupBy('ingredients.id', 'ingredients.name')
            ->orderByDesc('total_wastage')
            ->get();

        return [
            'ingredients_with_wastage' => $wastageData,
            'total_wastage' => $wastageData->sum('total_wastage'),
            'average_daily_wastage' => $wastageData->sum('total_wastage') / max(1, $startDate->diffInDays($endDate)),
            'wastage_frequency' => $wastageData->avg('wastage_days'),
        ];
    }
} 