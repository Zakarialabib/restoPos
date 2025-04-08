<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MetricsUpdated implements ShouldBroadcast
{
    public $metrics;

    public function broadcastOn()
    {
        return new PrivateChannel('metrics');
    }
}
