<?php


declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PriceManagementService
{
    public function updatePrice($model, float $newPrice, float $newCost, ?string $reason = null): void
    {
        DB::transaction(function () use ($model, $newPrice, $newCost, $reason): void {
            $previousPrice = $model->price;
            $previousCost = $model->cost;

            $model->prices()->create([
                'price' => $newPrice,
                'cost' => $newCost,
                'previous_price' => $previousPrice,
                'previous_cost' => $previousCost,
                'reason' => $reason,
                'is_current' => true,
                'entry_date' => now(),
            ]);

            $model->prices()->where('id', '!=', $model->prices()->latest()->first()->id)
                ->update(['is_current' => false]);

            $model->price = $newPrice;
            $model->cost = $newCost;
            $model->save();
        });
    }

    public function addSizeBasedPrice($model, string $size, string $unit, float $price, float $cost): void
    {
        DB::transaction(function () use ($model, $size, $unit, $price, $cost): void {
            $model->prices()->create([
                'price' => $price,
                'cost' => $cost,
                'is_current' => true,
                'entry_date' => now(),
                'metadata' => [
                    'size' => $size,
                    'unit' => $unit,
                ],
            ]);
        });
    }

    public function getPriceHistory($model): Collection
    {
        return $model->prices()
            ->orderByDesc('entry_date')
            ->get()
            ->map(fn ($price) => [
                'price' => $price->price,
                'cost' => $price->cost,
                'previous_price' => $price->previous_price,
                'previous_cost' => $price->previous_cost,
                'reason' => $price->reason,
                'entry_date' => $price->entry_date,
                'metadata' => $price->metadata,
            ]);
    }

    public function analyzePriceTrends($model, int $days = 30): array
    {
        $prices = $model->prices()
            ->where('entry_date', '>=', now()->subDays($days))
            ->orderBy('entry_date')
            ->get();

        if ($prices->isEmpty()) {
            return [
                'trend_direction' => 'stable',
                'volatility' => 0,
                'average_price' => $model->price,
                'price_change' => 0,
                'price_change_percentage' => 0,
            ];
        }

        $priceValues = $prices->pluck('price')->toArray();
        $firstPrice = $prices->first()->price;
        $lastPrice = $prices->last()->price;
        $averagePrice = array_sum($priceValues) / count($priceValues);

        // Calculate volatility
        $volatility = $this->calculateVolatility($priceValues);

        // Calculate trend direction
        $trendDirection = $this->calculateTrendDirection($firstPrice, $lastPrice, $volatility);

        // Calculate price change
        $priceChange = $lastPrice - $firstPrice;
        $priceChangePercentage = $firstPrice > 0 ? ($priceChange / $firstPrice) * 100 : 0;

        return [
            'trend_direction' => $trendDirection,
            'volatility' => $volatility,
            'average_price' => $averagePrice,
            'price_change' => $priceChange,
            'price_change_percentage' => $priceChangePercentage,
        ];
    }

    public function bulkUpdatePrices(array $ids, float $price, float $cost, ?string $reason = null): void
    {
        DB::transaction(function () use ($ids, $price, $cost, $reason): void {
            foreach ($ids as $id) {
                $model = Product::find($id) ?? Ingredient::find($id);
                if ($model) {
                    $this->updatePrice($model, $price, $cost, $reason);
                }
            }
        });
    }

    protected function calculateVolatility(array $prices): float
    {
        if (count($prices) < 2) {
            return 0;
        }

        $mean = array_sum($prices) / count($prices);
        $variance = array_reduce($prices, fn ($carry, $price) => $carry + pow($price - $mean, 2), 0) / (count($prices) - 1);

        return sqrt($variance);
    }

    protected function calculateTrendDirection(float $firstPrice, float $lastPrice, float $volatility): string
    {
        $priceChange = $lastPrice - $firstPrice;
        $significantChange = abs($priceChange) > $volatility;

        if ( ! $significantChange) {
            return 'stable';
        }

        return $priceChange > 0 ? 'increasing' : 'decreasing';
    }
}
