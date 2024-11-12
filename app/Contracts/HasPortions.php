<?php

declare(strict_types=1);

namespace App\Contracts;

interface HasPortions
{
    public function calculateBasePortion(int $itemCount): float;
    public function calculateItemPortion(int $itemCount): float;
    public function getPortionByType(string $type, int $itemCount): float;
    public function validatePortions(array $portions): bool;
}
