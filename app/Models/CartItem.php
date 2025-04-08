<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'name',
        'price',
        'quantity',
        'is_custom',
        'custom_data',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'is_custom' => 'boolean',
        'custom_data' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
