<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\StockLog;
use App\Services\InventoryManagementService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasInventory
{
    protected ?InventoryManagementService $inventoryService = null;

    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    public function getCurrentStock(): float
    {
        return $this->stock_quantity ?? 0;
    }

    public function adjustStock(float $quantity, ?string $reason = null): void
    {
        $this->getInventoryService()->adjustStock($this, $quantity, $reason);
    }

    public function hasRequiredStock(?float $quantity = null): bool
    {
        return $this->getInventoryService()->checkStockAvailability($this, $quantity);
    }

    public function updateReorderPoint(float $reorderPoint): void
    {
        $this->getInventoryService()->updateReorderPoint($this, $reorderPoint);
    }

    public function calculateReorderQuantity(): float
    {
        return $this->reorder_point ?? 0;
    }

    public function needsReorder(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    public function isLowStock(): bool
    {
        return 'low_stock' === $this->stock_status;
    }

    public function isOutOfStock(): bool
    {
        return 'out_of_stock' === $this->stock_status;
    }

    public function isInStock(): bool
    {
        return 'in_stock' === $this->stock_status;
    }

    protected function getInventoryService(): InventoryManagementService
    {
        if ( ! $this->inventoryService) {
            $this->inventoryService = app(InventoryManagementService::class);
        }

        return $this->inventoryService;
    }

    protected function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock_quantity <= $this->reorder_point) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    protected function stockStatusLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->stock_status) {
                'out_of_stock' => 'Out of Stock',
                'low_stock' => 'Low Stock',
                'in_stock' => 'In Stock',
                default => 'Unknown'
            }
        );
    }

    protected function stockStatusColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->stock_status) {
                'out_of_stock' => 'red',
                'low_stock' => 'yellow',
                'in_stock' => 'green',
                default => 'gray'
            }
        );
    }

    protected function stockLevel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->stock_quantity <= 0) {
                    return 'Out of Stock';
                }
                if ($this->stock_quantity <= $this->reorder_point) {
                    return 'Low Stock';
                }
                return 'In Stock';

            }
        );
    }
}
