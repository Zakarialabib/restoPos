<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Price;
use App\Services\CostManagementService;
use App\Services\PriceManagementService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait HasPricing
{
    protected ?CostManagementService $costManagementService = null;
    protected ?PriceManagementService $priceManagementService = null;

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function currentPrice(): Price|null
    {
        return $this->prices()->where('is_current', true)->first();
    }

    public function getCurrentPrice(): float
    {
        return (float) ($this->currentPrice()?->price ?? 0.0);
    }

    public function getCurrentCost(): float
    {
        return (float) ($this->currentPrice()?->cost ?? 0.0);
    }

    // Price Management Methods
    public function updatePrice(float $price, float $cost, ?string $reason = null): Price
    {
        // Mark current price as not current
        $this->prices()->where('is_current', true)->update(['is_current' => false]);

        // Create new price record
        return $this->prices()->create([
            'price' => (float) $price,
            'cost' => (float) $cost,
            'previous_price' => (float) $this->getCurrentPrice(),
            'previous_cost' => (float) $this->getCurrentCost(),
            'effective_date' => now(),
            'is_current' => true,
            'reason' => $reason,
        ]);
    }

    public function addSizeBasedPrice(string $size, string $unit, float $price, float $cost): void
    {
        $this->getPriceManagementService()->addSizeBasedPrice(
            $this,
            $size,
            $unit,
            (float) $price,
            (float) $cost
        );
    }

    public function getPriceHistory(): array
    {
        return $this->prices()
            ->orderByDesc('effective_date')
            ->get()
            ->map(fn (Price $price) => [
                'price' => (float) $price->price,
                'cost' => (float) $price->cost,
                'effective_date' => $price->effective_date,
                'reason' => $price->reason,
            ])
            ->toArray();
    }

    // Cost Management Methods
    public function updateCost(float $cost, ?string $reason = null): void
    {
        $this->getCostManagementService()->updateCost($this, (float) $cost, $reason);
    }

    public function getCostHistory(): Collection
    {
        return $this->getCostManagementService()->getCostHistory($this);
    }

    public function getAverageCost(int $days = 30): float
    {
        return (float) $this->getCostManagementService()->getAverageCost($this, $days);
    }

    public function analyzeCostTrends(int $days = 30): array
    {
        return $this->getCostManagementService()->analyzeCostTrends($this, $days);
    }

    // Helper Methods
    public function hasSizePrices(): bool
    {
        return $this->prices()
            ->whereNotNull('metadata->size')
            ->exists();
    }

    public function getSizePrice(string $size): ?Price
    {
        return $this->prices()
            ->where('metadata->size', $size)
            ->latest()
            ->first();
    }

    public function getBasePriceWithoutSizes(): ?Price
    {
        return $this->prices()
            ->whereNull('metadata->size')
            ->latest()
            ->first();
    }

    protected function getPriceManagementService(): PriceManagementService
    {
        if ( ! $this->priceManagementService) {
            $this->priceManagementService = app(PriceManagementService::class);
        }
        return $this->priceManagementService;
    }

    protected function getCostManagementService(): CostManagementService
    {
        if ( ! $this->costManagementService) {
            $this->costManagementService = app(CostManagementService::class);
        }
        return $this->costManagementService;
    }

    // Computed Properties
    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if ($this->getCurrentPrice() <= 0) {
                    return 0;
                }
                return (($this->getCurrentPrice() - $this->getCurrentCost()) / $this->getCurrentPrice()) * 100;
            }
        );
    }

    protected function profit(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->getCurrentPrice() - $this->getCurrentCost()
        );
    }

    protected function costTrend(): Attribute
    {
        return Attribute::make(
            get: fn (): array => $this->analyzeCostTrends()
        );
    }

    protected function costVolatility(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $trends = $this->analyzeCostTrends();
                return $trends['volatility'];
            }
        );
    }

    protected function costTrendDirection(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $trends = $this->analyzeCostTrends();
                return $trends['trend_direction'];
            }
        );
    }
}
