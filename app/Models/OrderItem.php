<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'details' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function composableJuice()
    {
        return $this->belongsTo(Composable::class);
    }

    // Accessor for the details (JSON)
    public function getDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    // Methods
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    // Mutator for the details (JSON)
    public function setDetailsAttribute($value): void
    {
        $this->attributes['details'] = json_encode($value);
    }

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->save();
        $this->order->calculateTotal();
    }
}
