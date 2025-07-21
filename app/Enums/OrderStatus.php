<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PREPARING = 'preparing';
    case READY = 'ready';
    case IN_DELIVERY = 'in_delivery';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case ON_HOLD = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::PREPARING => 'Preparing',
            self::READY => 'Ready for Pickup/Delivery',
            self::IN_DELIVERY => 'In Delivery',
            self::DELIVERED => 'Delivered',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
            self::ON_HOLD => 'On Hold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::CONFIRMED => 'blue',
            self::PREPARING => 'yellow',
            self::READY => 'green',
            self::IN_DELIVERY => 'purple',
            self::DELIVERED => 'indigo',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'red',
            self::REFUNDED => 'pink',
            self::ON_HOLD => 'orange',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'clock',
            self::PREPARING => 'refresh',
            self::COMPLETED => 'check',
            self::CANCELLED => 'x',
            self::REFUNDED => 'arrow-left',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'Order received but not yet confirmed',
            self::CONFIRMED => 'Order confirmed and payment verified',
            self::PREPARING => 'Order is being prepared in the kitchen',
            self::READY => 'Order is ready for pickup or delivery',
            self::IN_DELIVERY => 'Order is out for delivery',
            self::DELIVERED => 'Order has been delivered to the customer',
            self::COMPLETED => 'Order fulfilled and transaction completed',
            self::CANCELLED => 'Order has been cancelled',
            self::REFUNDED => 'Payment has been refunded to customer',
            self::ON_HOLD => 'Order temporarily paused',
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::PENDING => in_array($newStatus, [self::CONFIRMED, self::CANCELLED, self::ON_HOLD]),
            self::CONFIRMED => in_array($newStatus, [self::PREPARING, self::CANCELLED, self::ON_HOLD]),
            self::PREPARING => in_array($newStatus, [self::READY, self::CANCELLED, self::ON_HOLD]),
            self::READY => in_array($newStatus, [self::IN_DELIVERY, self::COMPLETED, self::CANCELLED]),
            self::IN_DELIVERY => in_array($newStatus, [self::DELIVERED, self::CANCELLED]),
            self::DELIVERED => in_array($newStatus, [self::COMPLETED, self::REFUNDED]),
            self::COMPLETED => in_array($newStatus, [self::REFUNDED]),
            self::CANCELLED => in_array($newStatus, [self::REFUNDED]),
            self::REFUNDED => false,
            self::ON_HOLD => in_array($newStatus, [self::PREPARING, self::CANCELLED]),
        };
    }
}
