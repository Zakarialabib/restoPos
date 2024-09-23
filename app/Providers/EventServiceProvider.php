<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ProductStockUpdated;
use App\Listeners\UpdateProductAvailability;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProductStockUpdated::class => [
            UpdateProductAvailability::class,
        ],
    ];
}