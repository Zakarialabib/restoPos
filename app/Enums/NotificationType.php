<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationType: string
{
    case STOCK_ALERT = 'stock_alert';
    case ORDER_UPDATE = 'order_update';
    case SYSTEM_ALERT = 'system_alert';
    case USER_NOTIFICATION = 'user_notification';
    case ORDER_CONFIRMATION = 'order_confirmation';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_DELIVERED = 'order_delivered';
    case ORDER_PREPARED = 'order_prepared';
    case ORDER_READY = 'order_ready';
    case ORDER_PLACED = 'order_placed';

    public static function getNotificationTypes(): array
    {
        return [
            self::STOCK_ALERT, self::ORDER_UPDATE, self::SYSTEM_ALERT, self::USER_NOTIFICATION, self::ORDER_CONFIRMATION, self::ORDER_CANCELLED, self::ORDER_DELIVERED, self::ORDER_PREPARED, self::ORDER_READY, self::ORDER_PLACED
        ];
    }

}
