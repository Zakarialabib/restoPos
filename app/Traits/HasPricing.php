<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPricing
{
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function getCurrentPrice()
    {
        return $this->prices()->where('is_current', true)->first();
    }

    public function addPrice(float $cost, float $price, ?string $notes = null, ?Carbon $date = null): Price
    {
        // Deactivate previous current prices
        $this->prices()->update(['is_current' => false]);

        return $this->prices()->create([
            'cost' => $cost,
            'price' => $price,
            'notes' => $notes,
            'date' => $date ?? now(),
            'is_current' => true,
        ]);
    }

    public function calculateProfitMargin(): ?float
    {
        $currentPrice = $this->getCurrentPrice();

        if ( ! $currentPrice) {
            return null;
        }

        if (0 === $currentPrice->price) {
            return 0;
        }

        return (($currentPrice->price - $currentPrice->cost) / $currentPrice->price) * 100;
    }
}
