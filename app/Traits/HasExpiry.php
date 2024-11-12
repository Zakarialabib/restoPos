<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\User;
use App\Notifications\ExpiryAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

trait HasExpiry
{
    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expiry_date && $this->expiry_date->lte(now()->addDays($days));
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    protected function handleExpiringSoon(): void
    {
        // Override in model if needed
        $user = User::first();
        Notification::send($user, new ExpiryAlert($this));
    }
}
