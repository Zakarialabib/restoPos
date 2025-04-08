<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\KitchenOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewKitchenOrder implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly KitchenOrder $order
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('kitchen'),
            new PrivateChannel('kitchen.orders'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewKitchenOrder';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'reference' => $this->order->order->reference,
            'items_count' => $this->order->items->count(),
            'created_at' => $this->order->created_at->toIso8601String(),
        ];
    }
}
