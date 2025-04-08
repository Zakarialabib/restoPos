<?php

declare(strict_types=1);

namespace App\Enums;

enum KitchenOrderStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Delayed = 'delayed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Delayed => 'Delayed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::InProgress => 'blue',
            self::Completed => 'green',
            self::Cancelled => 'red',
            self::Delayed => 'orange',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending => 'clock',
            self::InProgress => 'refresh',
            self::Completed => 'check',
            self::Cancelled => 'x',
            self::Delayed => 'hourglass',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Order received but not yet started',
            self::InProgress => 'Order is being prepared in the kitchen',
            self::Completed => 'Order preparation completed',
            self::Cancelled => 'Order preparation has been cancelled',
            self::Delayed => 'Order preparation has been delayed',
        };
    }
}
