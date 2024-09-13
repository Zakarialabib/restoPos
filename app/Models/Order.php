<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\LowStockAlert;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::updated(function ($order): void {
            if ($order->isDirty('status')) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                ]);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateInventory(): void
    {
        $this->load('items.product.ingredients');

        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                foreach ($product->ingredients as $ingredient) {
                    if ($ingredient) {
                        $quantity = $ingredient->pivot->quantity * $item->quantity;
                        $ingredient->decrement('quantity', $quantity);

                        if ($ingredient->quantity <= $ingredient->reorder_level) {
                            Notification::route('mail', 'admin@example.com')
                                ->notify(new LowStockAlert($ingredient));
                        }
                    }
                }
            }
        }
    }
}
