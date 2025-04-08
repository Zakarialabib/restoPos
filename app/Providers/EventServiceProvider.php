<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\OrderCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            //
        ],
    ];

    public function boot(): void
    {
        // Register Reverb channels
        $this->registerReverbChannels();
    }

    protected function registerReverbChannels(): void
    {
        // Private channels
        Broadcast::channel('dashboard', fn($user) => $user->isAdmin());

        Broadcast::channel('orders.{orderId}', fn($user, $orderId) => $user->canAccessOrder($orderId));
    }
}
