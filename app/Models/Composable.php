<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\HasPortions;
use App\Contracts\HasPricing;
use App\Traits\HasSizes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Composable extends Model implements HasPricing, HasPortions
{
    use HasSizes;

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function getCurrentPrice(): ?Price
    {
        return $this->prices()->latest()->first();
    }

    public function calculatePrice(): float
    {
        return $this->getCurrentPrice()?->price ?? 0.0;
    }

    public function validatePrice(float $price): bool
    {
        return $price >= 0;
    }

    public function calculateBasePortion(int $itemCount): float
    {
        return match ($itemCount) {
            1 => 0.6,
            2 => 0.5,
            3 => 0.4,
            4 => 0.3,
            5 => 0.2,
            default => 0.6
        };
    }

    public function calculateItemPortion(int $itemCount): float
    {
        $basePortion = $this->calculateBasePortion($itemCount);
        return (1 - $basePortion) / ($itemCount ?: 1);
    }

    public function getPortionByType(string $type, int $itemCount): float
    {
        return match ($type) {
            'fruit' => $this->calculateItemPortion($itemCount),
            'base' => $this->calculateBasePortion($itemCount),
            'addon' => 0.1,
            default => 0.0
        };
    }

    public function calculatePortionPrice(string $type, float $portion): float
    {
        $sizeMultiplier = $this->getSizeMultiplier();
        return $this->getCurrentPrice()?->price * $portion * $sizeMultiplier;
    }

    public function validatePortions(array $portions): bool
    {
        return array_sum($portions) <= 1.0;
    }
}
