<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Order $order
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dashboard'),
            new PrivateChannel("orders.{$this->order->id}")
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'total' => $this->order->total_amount,
            'created_at' => $this->order->created_at->toISOString(),
            'customer_name' => $this->order->customer_name,
        ];
    }
}
