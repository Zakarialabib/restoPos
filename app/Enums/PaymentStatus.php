<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
            self::Refunded => 'Refunded',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Processing => 'blue',
            self::Completed => 'green',
            self::Failed => 'red',
            self::Refunded => 'gray',
            self::Cancelled => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending => 'clock',
            self::Processing => 'refresh',
            self::Completed => 'check',
            self::Failed => 'x',
            self::Refunded => 'arrow-left',
            self::Cancelled => 'ban',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Pending => 'Payment is awaiting processing',
            self::Processing => 'Payment is being processed',
            self::Completed => 'Payment has been completed successfully',
            self::Failed => 'Payment has failed',
            self::Refunded => 'Payment has been refunded',
            self::Cancelled => 'Payment has been cancelled',
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return match($this) {
            self::Pending => in_array($status, [self::Processing, self::Failed, self::Cancelled]),
            self::Processing => in_array($status, [self::Completed, self::Failed]),
            self::Completed => in_array($status, [self::Refunded]),
            self::Failed => false,
            self::Refunded => false,
            self::Cancelled => false,
        };
    }
}
