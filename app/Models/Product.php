<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_available',
        'image_url',
        'is_composable',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_composable' => 'boolean',
        'ingredients' => 'array',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}