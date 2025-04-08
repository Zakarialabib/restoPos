<?php


declare(strict_types=1);

namespace App\Traits;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

trait HasOrders
{
    protected ?OrderService $orderService = null;

    // Order Processing Methods
    public function processPayment(array $paymentData): void
    {
        $this->getOrderService()->processPayment($this, $paymentData);
    }

    public function processRefund(float $amount, ?string $reason = null): void
    {
        $this->getOrderService()->processRefund($this, $amount, $reason);
    }

    public function applyDiscount(float $amount, string $type = 'fixed', ?string $reason = null): void
    {
        $this->getOrderService()->applyDiscount($this, $amount, $type, $reason);
    }

    public function addItem(array $itemData): OrderItem
    {
        return $this->getOrderService()->addOrderItem($this, $itemData);
    }

    public function removeItem(int $orderItemId): void
    {
        $this->getOrderService()->removeOrderItem($this, $orderItemId);
    }

    public function updateStatus(OrderStatus $newStatus): void
    {
        $this->getOrderService()->updateOrderStatus($this, $newStatus);
    }

    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        return $this->getOrderService()->validateStatusTransition($this, $newStatus);
    }

    public function getAvailableStatuses(): array
    {
        return $this->getOrderService()->getAvailableStatuses($this);
    }

    // Analytics Methods
    public function getStatistics(Carbon $startDate, Carbon $endDate): array
    {
        return $this->getOrderService()->getOrderStatistics($startDate, $endDate);
    }

    public function getPopularItems(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return $this->getOrderService()->getPopularItems($startDate, $endDate, $limit);
    }


    // Status Checks
    public function isPending(): bool
    {
        return OrderStatus::Pending === $this->status;
    }

    public function isProcessing(): bool
    {
        return OrderStatus::Preparing === $this->status;
    }

    public function isCompleted(): bool
    {
        return OrderStatus::Completed === $this->status;
    }

    public function isCancelled(): bool
    {
        return OrderStatus::Cancelled === $this->status;
    }

    public function isRefunded(): bool
    {
        return OrderStatus::Refunded === $this->status;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            OrderStatus::Pending,
            OrderStatus::Preparing
        ]);
    }

    protected function getOrderService(): OrderService
    {
        if ( ! $this->orderService) {
            $this->orderService = app(OrderService::class);
        }
        return $this->orderService;
    }

    // Computed Properties
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->status) {
                    OrderStatus::Pending => 'Pending',
                    OrderStatus::Preparing => 'Processing',
                    OrderStatus::Completed => 'Completed',
                    OrderStatus::Cancelled => 'Cancelled',
                    OrderStatus::Refunded => 'Refunded',
                    default => 'Unknown'
                };
            }
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->status) {
                    OrderStatus::Pending => 'yellow',
                    OrderStatus::Preparing => 'blue',
                    OrderStatus::Completed => 'green',
                    OrderStatus::Cancelled => 'red',
                    OrderStatus::Refunded => 'purple',
                    default => 'gray'
                };
            }
        );
    }

    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->items->sum(fn ($item) => $item->price * $item->quantity)
        );
    }

    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->items->sum(fn ($item) => $item->cost * $item->quantity)
        );
    }

    protected function profit(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->total_amount
        );
    }

    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if ($this->total_amount <= 0) {
                    return 0;
                }
                return ($this->profit / $this->total_amount) * 100;
            }
        );
    }


}
