<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\KitchenOrderStatus;
use App\Models\KitchenOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KitchenOrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly KitchenOrder $order,
        public readonly KitchenOrderStatus $oldStatus,
        public readonly KitchenOrderStatus $newStatus
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
        return 'KitchenOrderStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
        ];
    }
}
