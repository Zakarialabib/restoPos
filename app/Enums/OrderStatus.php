<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Ready = 'ready';
    case InDelivery = 'in_delivery';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Preparing => 'Preparing',
            self::Ready => 'Ready for Pickup/Delivery',
            self::InDelivery => 'In Delivery',
            self::Delivered => 'Delivered',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
            self::OnHold => 'On Hold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Confirmed => 'blue',
            self::Preparing => 'yellow',
            self::Ready => 'green',
            self::InDelivery => 'purple',
            self::Delivered => 'indigo',
            self::Completed => 'emerald',
            self::Cancelled => 'red',
            self::Refunded => 'pink',
            self::OnHold => 'orange',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending => 'clock',
            self::Preparing => 'refresh',
            self::Completed => 'check',
            self::Cancelled => 'x',
            self::Refunded => 'arrow-left',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Order received but not yet confirmed',
            self::Confirmed => 'Order confirmed and payment verified',
            self::Preparing => 'Order is being prepared in the kitchen',
            self::Ready => 'Order is ready for pickup or delivery',
            self::InDelivery => 'Order is out for delivery',
            self::Delivered => 'Order has been delivered to the customer',
            self::Completed => 'Order fulfilled and transaction completed',
            self::Cancelled => 'Order has been cancelled',
            self::Refunded => 'Payment has been refunded to customer',
            self::OnHold => 'Order temporarily paused',
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::Pending => in_array($newStatus, [self::Confirmed, self::Cancelled, self::OnHold]),
            self::Confirmed => in_array($newStatus, [self::Preparing, self::Cancelled, self::OnHold]),
            self::Preparing => in_array($newStatus, [self::Ready, self::Cancelled, self::OnHold]),
            self::Ready => in_array($newStatus, [self::InDelivery, self::Completed, self::Cancelled]),
            self::InDelivery => in_array($newStatus, [self::Delivered, self::Cancelled]),
            self::Delivered => in_array($newStatus, [self::Completed, self::Refunded]),
            self::Completed => in_array($newStatus, [self::Refunded]),
            self::Cancelled => in_array($newStatus, [self::Refunded]),
            self::Refunded => false,
            self::OnHold => in_array($newStatus, [self::Preparing, self::Cancelled]),
        };
    }
}
