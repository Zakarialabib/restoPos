<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PredictionService
{
    public function getPredictedDemand(Carbon $startDate, Carbon $endDate): Collection
    {
        // Get historical data for the same period length before start date
        $periodLength = $startDate->diffInDays($endDate);
        $historicalStartDate = $startDate->copy()->subDays($periodLength);

        $historicalData = Order::whereBetween('created_at', [$historicalStartDate, $startDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calculate average daily growth rate
        $growthRate = $this->calculateGrowthRate($historicalData);

        // Generate predictions
        $predictions = collect();
        $currentDate = $startDate->copy();
        $lastValue = $historicalData->last()?->revenue ?? 0;

        while ($currentDate <= $endDate) {
            $predictedValue = $lastValue * (1 + $growthRate);
            $predictions->push([
                'date' => $currentDate->format('Y-m-d'),
                'revenue' => round($predictedValue, 2)
            ]);

            $lastValue = $predictedValue;
            $currentDate->addDay();
        }

        return $predictions;
    }

    public function predictStockNeeds(Product $product, int $days = 30): array
    {
        $historicalUsage = $this->getHistoricalUsage($product, $days);
        $avgDailyUsage = $historicalUsage->avg('quantity') ?? 0;
        $stdDeviation = $this->calculateStdDeviation($historicalUsage->pluck('quantity'));

        return [
            'product_id' => $product->id,
            'avg_daily_usage' => round($avgDailyUsage, 2),
            'recommended_stock' => round($avgDailyUsage * $days * 1.2 + $stdDeviation, 0), // 20% buffer + 1 std dev
            'confidence' => $this->calculateConfidenceScore($historicalUsage->count(), $stdDeviation, $avgDailyUsage)
        ];
    }

    private function calculateGrowthRate(Collection $historicalData): float
    {
        if ($historicalData->count() < 2) {
            return 0;
        }

        $dailyGrowthRates = collect();
        $previousValue = null;

        foreach ($historicalData as $data) {
            if ($previousValue && $previousValue > 0) {
                $growthRate = ($data->revenue - $previousValue) / $previousValue;
                $dailyGrowthRates->push($growthRate);
            }
            $previousValue = $data->revenue;
        }

        return $dailyGrowthRates->avg() ?? 0;
    }

    private function getHistoricalUsage(Product $product, int $days): Collection
    {
        return DB::table('order_items')
            ->where('product_id', $product->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(quantity) as quantity')
            )
            ->groupBy('date')
            ->get();
    }

    private function calculateStdDeviation(Collection $values): float
    {
        $avg = $values->avg() ?? 0;
        $squaredDiffs = $values->map(fn ($value) => pow($value - $avg, 2));
        return sqrt($squaredDiffs->avg() ?? 0);
    }

    private function calculateConfidenceScore(int $sampleSize, float $stdDev, float $mean): float
    {
        if ($sampleSize < 7) {
            return 0.5; // Low confidence for small samples
        }

        $coefficientOfVariation = $mean > 0 ? $stdDev / $mean : 1;
        $confidenceScore = 1 - min($coefficientOfVariation, 0.5);

        return round(max($confidenceScore, 0.1), 2);
    }
}
