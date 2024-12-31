<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Price;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPrice
{
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function getCurrentPrice(): ?float
    {
        return $this->prices()->latest()->first()?->amount;
    }

    public function setPrice(float $amount): void
    {
        $this->prices()->create([
            'amount' => $amount,
            'active_from' => now(),
        ]);
    }
}
