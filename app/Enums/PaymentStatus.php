<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::REFUNDED => 'gray',
            self::CANCELLED => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'clock',
            self::PROCESSING => 'refresh',
            self::COMPLETED => 'check',
            self::FAILED => 'x',
            self::REFUNDED => 'arrow-left',
            self::CANCELLED => 'ban',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::PENDING => 'Payment is awaiting processing',
            self::PROCESSING => 'Payment is being processed',
            self::COMPLETED => 'Payment has been completed successfully',
            self::FAILED => 'Payment has failed',
            self::REFUNDED => 'Payment has been refunded',
            self::CANCELLED => 'Payment has been cancelled',
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return match($this) {
            self::PENDING => in_array($status, [self::PROCESSING, self::FAILED, self::CANCELLED]),
            self::PROCESSING => in_array($status, [self::COMPLETED, self::FAILED]),
            self::COMPLETED => in_array($status, [self::REFUNDED]),
            self::FAILED => false,
            self::REFUNDED => false,
            self::CANCELLED => false,
        };
    }
}
