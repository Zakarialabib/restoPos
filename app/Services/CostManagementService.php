<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\HasPricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class CostManagementService
{
    public function updateCost(Model $model, float $cost, ?string $reason = null): void
    {
        if ( ! $this->hasPricingTrait($model)) {
            throw new InvalidArgumentException('Model must use HasPricing trait');
        }

        DB::transaction(function () use ($model, $cost, $reason): void {
            // Get current price record
            $currentPrice = $model->currentPrice();
            if ( ! $currentPrice) {
                throw new RuntimeException('No current price found for the model');
            }

            // Update the cost while keeping the same price
            $model->updatePrice(
                $currentPrice->price,
                $cost,
                $reason ?? 'Cost update'
            );
        });
    }

    public function getCostHistory(Model $model): Collection
    {
        if ( ! $this->hasPricingTrait($model)) {
            throw new InvalidArgumentException('Model must use HasPricing trait');
        }

        return $model->prices()
            ->orderByDesc('effective_date')
            ->get()
            ->map(fn ($price) => [
                'cost' => (float) $price->cost,
                'effective_date' => $price->effective_date,
                'reason' => $price->reason,
            ]);
    }

    public function getAverageCost(Model $model, int $days = 30): float
    {
        if ( ! $this->hasPricingTrait($model)) {
            throw new InvalidArgumentException('Model must use HasPricing trait');
        }

        $startDate = now()->subDays($days);
        $averageCost = $model->prices()
            ->where('effective_date', '>=', $startDate)
            ->avg('cost');

        return (float) ($averageCost ?? 0.0);
    }

    public function analyzeCostTrends(Model $model, int $days = 30): array
    {
        if ( ! $this->hasPricingTrait($model)) {
            throw new InvalidArgumentException('Model must use HasPricing trait');
        }

        $startDate = now()->subDays($days);
        $costs = $model->prices()
            ->where('effective_date', '>=', $startDate)
            ->orderBy('effective_date')
            ->pluck('cost')
            ->map(fn ($cost) => (float) $cost)
            ->toArray();

        if (empty($costs)) {
            return [
                'trend_direction' => 'stable',
                'volatility' => 0.0,
                'average_cost' => 0.0,
                'cost_change' => 0.0,
                'cost_change_percentage' => 0.0,
            ];
        }

        $firstCost = $costs[0];
        $lastCost = end($costs);
        $costChange = $lastCost - $firstCost;
        $costChangePercentage = $firstCost > 0 ? ($costChange / $firstCost) * 100 : 0;

        // Calculate volatility (standard deviation)
        $averageCost = array_sum($costs) / count($costs);
        $squaredDiffs = array_map(fn ($cost) => pow($cost - $averageCost, 2), $costs);
        $volatility = sqrt(array_sum($squaredDiffs) / count($costs));

        return [
            'trend_direction' => $costChange > 0 ? 'increasing' : ($costChange < 0 ? 'decreasing' : 'stable'),
            'volatility' => (float) $volatility,
            'average_cost' => (float) $averageCost,
            'cost_change' => (float) $costChange,
            'cost_change_percentage' => (float) $costChangePercentage,
        ];
    }

    protected function hasPricingTrait(Model $model): bool
    {
        return in_array(HasPricing::class, class_uses_recursive($model));
    }
}
