<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 0;
    case Processing = 1;
    case Completed = 2;
    case Cancelled = 3;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Processing => 'bg-blue-100 text-blue-800',
            self::Completed => 'bg-green-100 text-green-800',
            self::Cancelled => 'bg-red-100 text-red-800',
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Processing => 'processing',
            self::Completed => 'completed',
            self::Cancelled => 'cancelled',
        };
    }
}
