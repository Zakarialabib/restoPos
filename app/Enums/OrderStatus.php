<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Processing => 'blue',
            self::Completed => 'green',
            self::Cancelled => 'red',
            self::Refunded => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending => 'clock',
            self::Processing => 'refresh',
            self::Completed => 'check',
            self::Cancelled => 'x',
            self::Refunded => 'arrow-left',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Pending => 'Order has been placed but not yet processed',
            self::Processing => 'Order is being prepared',
            self::Completed => 'Order has been completed and delivered',
            self::Cancelled => 'Order was cancelled',
            self::Refunded => 'Order was refunded',
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return match($this) {
            self::Pending => in_array($status, [self::Processing, self::Cancelled]),
            self::Processing => in_array($status, [self::Completed, self::Cancelled]),
            self::Completed => in_array($status, [self::Refunded]),
            self::Cancelled => false,
            self::Refunded => false,
        };
    }
}
