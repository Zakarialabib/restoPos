<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\KitchenOrderPriority;
use App\Enums\KitchenOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KitchenOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'priority',
        'assigned_to',
        'started_at',
        'completed_at',
        'estimated_preparation_time',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_preparation_time' => 'integer',
        'status' => KitchenOrderStatus::class,
        'priority' => KitchenOrderPriority::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(KitchenOrderItem::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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

    public function isHighPriority(): bool
    {
        return KitchenOrderPriority::High === $this->priority;
    }

    public function isMediumPriority(): bool
    {
        return KitchenOrderPriority::Medium === $this->priority;
    }

    public function isLowPriority(): bool
    {
        return KitchenOrderPriority::Low === $this->priority;
    }
}
