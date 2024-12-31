<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\StockLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStock
{
    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    public function getCurrentStock(): float
    {
        return $this->stockLogs()->sum('quantity');
    }

    public function adjustStock(float $quantity, ?string $reason = null): void
    {
        $this->stockLogs()->create([
            'quantity' => $quantity,
            'reason' => $reason,
            'date' => now(),
        ]);
    }

    public function isLowStock(): bool
    {
        return $this->getCurrentStock() <= ($this->min_stock ?? 0);
    }
}
