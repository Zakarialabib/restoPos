<?php

declare(strict_types=1);

namespace App\Enums;

enum KitchenOrderPriority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function label(): string
    {
        return match ($this) {
            self::High => 'High',
            self::Medium => 'Medium',
            self::Low => 'Low',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::High => 'red',
            self::Medium => 'yellow',
            self::Low => 'blue',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::High => 'fire',
            self::Medium => 'clock',
            self::Low => 'snail',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::High => 'Urgent order that needs immediate attention',
            self::Medium => 'Standard priority order',
            self::Low => 'Order that can be prepared when other priorities are handled',
        };
    }
}
