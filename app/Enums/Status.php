<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: int
{
    case UNAVAILABLE = 0;
    case AVAILABLE = 1;
    case INACTIVE = 2;
    case OUT_OF_STOCK = 3;

    public static function getStatuses(): array
    {
        return [
            self::AVAILABLE,
            self::UNAVAILABLE,
            self::INACTIVE,
            self::OUT_OF_STOCK
        ];
    }

    public static function isAvailable(int $status): bool
    {
        return self::AVAILABLE === $status;
    }

    public static function isUnavailable(int $status): bool
    {
        return self::UNAVAILABLE === $status;
    }

    public static function isInactive(int $status): bool
    {
        return self::INACTIVE === $status;
    }

    public static function isOutOfStock(int $status): bool
    {
        return self::OUT_OF_STOCK === $status;
    }

}
