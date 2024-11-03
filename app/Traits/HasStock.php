<?php

declare(strict_types=1);

namespace App\Traits;

use App\Notifications\LowStockAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

trait HasStock
{
    public function isLowStock(): bool
    {
        return $this->stock <= $this->getLowStockThreshold();
    }

    public function hasEnoughStock(float $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    public function updateStock(float $quantity): void
    {
        $this->stock += $quantity;
        $this->save();

        if ($this->isLowStock()) {
            $this->handleLowStock();
        }
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereRaw('stock <= ' . $this->getLowStockThresholdColumn());
    }

    protected function getLowStockThreshold(): int
    {
        return $this->low_stock_threshold ?? 10;
    }

    protected function getLowStockThresholdColumn(): string
    {
        return $this->lowStockThresholdColumn ?? 'low_stock_threshold';
    }

    protected function handleLowStock(): void
    {
        // Override in model if needed
        Notification::send($this->users, new LowStockAlert($this));
    }
}
