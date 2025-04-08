<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Support\HasAdvancedFilter;
use App\Traits\HasOrders;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasOrders;
    use HasUuids;
    use LogsActivity;
    use Prunable;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'user_id'
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'id',
        'reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'discount_amount',
        'tax_amount',
        'refunded_amount',
        'shipping_address',
        'billing_address',
        'delivery_date',
        'preparation_notes',
        'source',
        'user_id',
        'cash_register_id',
        'guest_token',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'delivery_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subYears(2))
            ->where('status', OrderStatus::Completed);
    }

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    public function updateStatus(OrderStatus $newStatus): bool
    {
        if ( ! $this->status->canTransitionTo($newStatus)) {
            return false;
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        event(new OrderStatusChanged($this, $oldStatus, $newStatus));

        return true;
    }

    public function cancel(): bool
    {
        if ( ! $this->updateStatus(OrderStatus::Cancelled)) {
            return false;
        }

        // Restore inventory for cancelled items
        foreach ($this->items as $item) {
            // Implement inventory restoration logic here
            // This would depend on your inventory management system
        }

        return true;
    }

    public function refund(?float $amount = null): bool
    {
        $refundAmount = $amount ?? ($this->total_amount - $this->refunded_amount);

        if ($refundAmount <= 0 || $refundAmount > ($this->total_amount - $this->refunded_amount)) {
            return false;
        }

        $this->refunded_amount += $refundAmount;

        if ($this->refunded_amount >= $this->total_amount) {
            $this->updateStatus(OrderStatus::Refunded);
        }

        return $this->save();
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn ($item) => $item->quantity * $item->unit_price);
    }

    public function getFinalTotalAttribute(): float
    {
        return $this->subtotal + $this->tax_amount - $this->total_discount;
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::Pending);
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', [
            OrderStatus::Confirmed,
            OrderStatus::Preparing,
            OrderStatus::Ready,
            OrderStatus::InDelivery,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', OrderStatus::Completed);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatus::Cancelled);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', OrderStatus::Refunded);
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', OrderStatus::OnHold);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }


    public function scopeForPeriod(Builder $query, Carbon $startDate, Carbon $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order): void {
            if ( ! $order->reference) {
                $order->reference = self::generateReference();
            }
        });
    }

    protected static function generateReference(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) mb_substr($lastOrder->reference, -4) + 1 : 1;

        return sprintf('%s%s%04d', $prefix, $date, $sequence);
    }

    protected static function getActivityDescription(string $action, Model $model): string
    {
        $modelName = class_basename($model);

        return match ($action) {
            'created' => "New order #{$model->reference} was created",
            'updated' => "Order #{$model->reference} was updated",
            'deleted' => "Order #{$model->reference} was deleted",
            'status_changed' => "Order #{$model->reference} status changed",
            'priority_changed' => "Order #{$model->reference} priority changed",
            default => "Action {$action} performed on order #{$model->reference}",
        };
    }

    protected static function getActivityProperties(Model $model): array
    {
        $properties = [
            'model' => get_class($model),
            'id' => $model->id,
            'reference' => $model->reference,
            'changes' => $model->getChanges(),
        ];

        if ($model->wasChanged('status')) {
            $properties['status_change'] = [
                'from' => $model->getOriginal('status'),
                'to' => $model->status,
            ];
        }

        return $properties;
    }
}
