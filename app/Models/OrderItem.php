<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'quantity',
        'price',
        'details',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'details' => 'json',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function composableJuice(): BelongsTo
    {
        return $this->belongsTo(Composable::class);
    }

    protected function details(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    // Methods
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->save();
        $this->order->calculateTotal();
    }
}
