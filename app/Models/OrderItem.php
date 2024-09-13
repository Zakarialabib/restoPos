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
        'quantity',
        'price',
        'details'
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

    // Accessor for the details (JSON)
    public function getDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    // Mutator for the details (JSON)
    public function setDetailsAttribute($value): void
    {
        $this->attributes['details'] = json_encode($value);
    }
}
