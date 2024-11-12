<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Price;

interface HasPricing
{
    public function getCurrentPrice(): ?Price;
    public function calculatePrice(): float;
    public function calculatePortionPrice(string $type, float $portion): float;
    public function validatePrice(float $price): bool;
}
