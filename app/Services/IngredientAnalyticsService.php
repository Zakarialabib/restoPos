<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class IngredientAnalyticsService
{
    public function __construct(
        protected CostManagementService $costService
    ) {
    }

    public function getIngredientAnalytics(Carbon $startDate, Carbon $endDate): Collection
    {
        return Ingredient::query()
            ->with(['category', 'prices'])
            ->get()
            ->map(function (Ingredient $ingredient) use ($startDate, $endDate) {
                $costTrends = $this->costService->analyzeCostTrends($ingredient);
                $currentPrice = $ingredient->currentPrice();

                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'category' => $ingredient->category?->name,
                    'current_stock' => $ingredient->stock_quantity,
                    'reorder_point' => $ingredient->reorder_point,
                    'unit' => $ingredient->unit,
                    'current_cost' => $currentPrice?->cost ?? 0.0,
                    'current_price' => $currentPrice?->price ?? 0.0,
                    'cost_trend' => $costTrends['trend_direction'],
                    'cost_volatility' => $costTrends['volatility'],
                    'cost_change' => $costTrends['cost_change'],
                    'cost_change_percentage' => $costTrends['cost_change_percentage'],
                    'stock_status' => $ingredient->stock_status,
                    'days_until_reorder' => $this->calculateDaysUntilReorder($ingredient),
                    'should_reorder' => $this->shouldReorder($ingredient),
                    'last_purchase_date' => $this->getLastPurchaseDate($ingredient),
                    'average_consumption' => $this->calculateAverageConsumption($ingredient, $startDate, $endDate),
                ];
            });
    }

    public function getWastageAnalytics(Carbon $startDate, Carbon $endDate): Collection
    {
        return Ingredient::query()
            ->with(['category', 'prices'])
            ->get()
            ->map(function (Ingredient $ingredient) use ($startDate, $endDate) {
                $wastage = $this->calculateWastage($ingredient, $startDate, $endDate);
                $currentPrice = $ingredient->currentPrice();

                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'category' => $ingredient->category?->name,
                    'wastage_amount' => $wastage['amount'],
                    'wastage_cost' => $wastage['cost'],
                    'wastage_percentage' => $wastage['percentage'],
                    'current_cost' => $currentPrice?->cost ?? 0.0,
                ];
            });
    }

    public function getCostAnalytics(Carbon $startDate, Carbon $endDate): Collection
    {
        return Ingredient::query()
            ->with(['category', 'prices'])
            ->get()
            ->map(function (Ingredient $ingredient) {
                $costTrends = $this->costService->analyzeCostTrends($ingredient);
                $averageCost = $this->costService->getAverageCost($ingredient);
                $currentPrice = $ingredient->currentPrice();

                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'category' => $ingredient->category?->name,
                    'current_cost' => $currentPrice?->cost ?? 0.0,
                    'average_cost' => $averageCost,
                    'cost_trend' => $costTrends['trend_direction'],
                    'cost_volatility' => $costTrends['volatility'],
                    'cost_change' => $costTrends['cost_change'],
                    'cost_change_percentage' => $costTrends['cost_change_percentage'],
                ];
            });
    }

    public function getTurnoverAnalytics(Carbon $startDate, Carbon $endDate): Collection
    {
        return Ingredient::query()
            ->with(['category', 'prices'])
            ->get()
            ->map(function (Ingredient $ingredient) use ($startDate, $endDate) {
                $turnover = $this->calculateTurnover($ingredient, $startDate, $endDate);
                $currentPrice = $ingredient->currentPrice();

                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'category' => $ingredient->category?->name,
                    'turnover_rate' => $turnover['rate'],
                    'turnover_days' => $turnover['days'],
                    'current_cost' => $currentPrice?->cost ?? 0.0,
                    'average_stock' => $turnover['average_stock'],
                ];
            });
    }

    public function getSeasonalityAnalytics(): Collection
    {
        return Ingredient::query()
            ->with(['category', 'prices'])
            ->get()
            ->map(function (Ingredient $ingredient) {
                $seasonality = $this->calculateSeasonality($ingredient);
                $currentPrice = $ingredient->currentPrice();

                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'category' => $ingredient->category?->name,
                    'seasonality_score' => $seasonality['score'],
                    'peak_months' => $seasonality['peak_months'],
                    'low_months' => $seasonality['low_months'],
                    'current_cost' => $currentPrice?->cost ?? 0.0,
                ];
            });
    }

    protected function calculateWastage(Ingredient $ingredient, Carbon $startDate, Carbon $endDate): array
    {
        // Implementation for wastage calculation
        return [
            'amount' => 0.0,
            'cost' => 0.0,
            'percentage' => 0.0,
        ];
    }

    protected function calculateTurnover(Ingredient $ingredient, Carbon $startDate, Carbon $endDate): array
    {
        // Implementation for turnover calculation
        return [
            'rate' => 0.0,
            'days' => 0,
            'average_stock' => 0.0,
        ];
    }

    protected function calculateSeasonality(Ingredient $ingredient): array
    {
        // Implementation for seasonality calculation
        return [
            'score' => 0.0,
            'peak_months' => [],
            'low_months' => [],
        ];
    }

    protected function calculateDaysUntilReorder(Ingredient $ingredient): int
    {
        if ($ingredient->stock_quantity <= 0) {
            return 0;
        }

        $dailyUsage = $this->calculateDailyUsage($ingredient);
        if ($dailyUsage <= 0) {
            return PHP_INT_MAX;
        }

        return (int) ceil(($ingredient->stock_quantity - $ingredient->reorder_point) / $dailyUsage);
    }

    protected function shouldReorder(Ingredient $ingredient): bool
    {
        return $ingredient->stock_quantity <= $ingredient->reorder_point;
    }

    protected function getLastPurchaseDate(Ingredient $ingredient): ?Carbon
    {
        return $ingredient->stockLogs()
            ->where('type', 'purchase')
            ->latest('created_at')
            ->value('created_at');
    }

    protected function calculateAverageConsumption(Ingredient $ingredient, Carbon $startDate, Carbon $endDate): float
    {
        $logs = $ingredient->stockLogs()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'consumption')
            ->get();

        if ($logs->isEmpty()) {
            return 0.0;
        }

        return (float) ($logs->sum('quantity') / $logs->count());
    }

    protected function calculateDailyUsage(Ingredient $ingredient): float
    {
        // Implementation for daily usage calculation
        return 0.0;
    }
}
