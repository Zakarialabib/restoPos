<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly OrderStatus $oldStatus,
        public readonly OrderStatus $newStatus,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders'),
            new PrivateChannel('order.' . $this->order->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'reference' => $this->order->reference,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
