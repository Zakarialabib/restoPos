<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\KitchenOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitchenOrderItem extends Model
{
    protected $fillable = [
        'kitchen_order_id',
        'order_item_id',
        'status',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => KitchenOrderStatus::class,
    ];

    public function kitchenOrder(): BelongsTo
    {
        return $this->belongsTo(KitchenOrder::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function isPending(): bool
    {
        return KitchenOrderStatus::Pending === $this->status;
    }

    public function isInProgress(): bool
    {
        return KitchenOrderStatus::InProgress === $this->status;
    }

    public function isCompleted(): bool
    {
        return KitchenOrderStatus::Completed === $this->status;
    }

    public function isCancelled(): bool
    {
        return KitchenOrderStatus::Cancelled === $this->status;
    }
}
