<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderStatusService
{
    private array $allowedTransitions = [
        OrderStatus::Pending->value => [
            OrderStatus::Preparing->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Preparing->value => [
            OrderStatus::Completed->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Completed->value => [
            OrderStatus::Refunded->value,
        ],
        OrderStatus::Cancelled->value => [],
        OrderStatus::Refunded->value => [],
    ];

    public function updateStatus(Order $order, OrderStatus $newStatus): void
    {
        if ( ! $this->canTransitionTo($order->status, $newStatus)) {
            throw new Exception("Cannot transition from {$order->status->value} to {$newStatus->value}");
        }

        DB::transaction(function () use ($order, $newStatus): void {
            $oldStatus = $order->status;
            $order->status = $newStatus;

            // Handle status-specific logic
            match ($newStatus) {
                OrderStatus::Preparing => $this->handleProcessingStatus($order),
                OrderStatus::Completed => $this->handleCompletedStatus($order),
                OrderStatus::Cancelled => $this->handleCancelledStatus($order),
                OrderStatus::Refunded => $this->handleRefundedStatus($order),
                default => null,
            };

            $order->status_changed_at = now();
            $order->save();

            // You might want to dispatch events here
            // event(new OrderStatusChanged($order, $oldStatus, $newStatus));
        });
    }

    public function canTransitionTo(OrderStatus $currentStatus, OrderStatus $newStatus): bool
    {
        return in_array(
            $newStatus->value,
            $this->allowedTransitions[$currentStatus->value] ?? []
        );
    }

    public function getAvailableStatuses(Order $order): array
    {
        return $this->allowedTransitions[$order->status->value] ?? [];
    }

    public function validateStatusTransition(Order $order, OrderStatus $newStatus): bool
    {
        if ( ! $this->canTransitionTo($order->status, $newStatus)) {
            return false;
        }

        return match ($newStatus) {
            OrderStatus::Preparing => $this->canProcessOrder($order),
            OrderStatus::Completed => $this->canCompleteOrder($order),
            OrderStatus::Cancelled => $this->canCancelOrder($order),
            OrderStatus::Refunded => $this->canRefundOrder($order),
            default => true,
        };
    }

    private function handleProcessingStatus(Order $order): void
    {
        // Validate inventory
        foreach ($order->items as $item) {
            if ( ! $item->product->hasRequiredStock()) {
                throw new Exception("Product {$item->product->name} is out of stock");
            }
        }
    }

    private function handleCompletedStatus(Order $order): void
    {
        if (PaymentStatus::Completed !== $order->payment_status) {
            throw new Exception('Cannot complete order: Payment not completed');
        }
    }

    private function handleCancelledStatus(Order $order): void
    {
        // Restore stock for all items
        foreach ($order->items as $item) {
            $item->product->adjustStock(
                $item->quantity,
                'Order Cancelled: ' . $order->id
            );
        }
    }

    private function handleRefundedStatus(Order $order): void
    {
        if (PaymentStatus::Refunded !== $order->payment_status) {
            throw new Exception('Cannot mark as refunded: Refund not processed');
        }
    }

    private function canProcessOrder(Order $order): bool
    {
        return $order->items->every(fn ($item) => $item->product->hasRequiredStock());
    }

    private function canCompleteOrder(Order $order): bool
    {
        return PaymentStatus::Completed === $order->payment_status;
    }

    private function canCancelOrder(Order $order): bool
    {
        return ! in_array($order->status, [
            OrderStatus::Completed,
            OrderStatus::Refunded,
        ]);
    }

    private function canRefundOrder(Order $order): bool
    {
        return OrderStatus::Completed === $order->status &&
            PaymentStatus::Refunded === $order->payment_status;
    }
}
