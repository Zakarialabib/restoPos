<?php

namespace App\Enums;

enum Status: int
{
    case AVAILABLE = 1;
    case UNAVAILABLE = 0;
    case INACTIVE = 2;
    case OUT_OF_STOCK = 3;

    public static function getStatuses(): array
    {
        return [
            self::AVAILABLE,
            self::UNAVAILABLE,
            self::INACTIVE
        ];
    }

    public static function isAvailable(int $status): bool
    {
        return $status === self::AVAILABLE;
    }

    public static function isUnavailable(int $status): bool
    {
        return $status === self::UNAVAILABLE;
    }

    public static function isInactive(int $status): bool
    {
        return $status === self::INACTIVE;
    }
}
