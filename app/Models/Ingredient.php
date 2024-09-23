<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\LowStockAlert;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Ingredient
 * Represents an ingredient in the inventory.
 */


class Ingredient extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'type',
        'unit',
        'conversion_rate',
        'stock',
        'batch_number',
        'expiry_date',
        'volume',
        'reorder_level',
    ];

    // Define the inverse relationship with ComposableJuice
    public function composableJuice()
    {
        return $this->belongsTo(Composable::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('stock');
    }

    public function updateStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new \Exception('Insufficient stock to update.');
        }

        $this->stock -= $quantity;
        $this->save();

        if ($this->stock < 10) {
            // Trigger low stock alert
            $this->notify(new LowStockAlert($this));
        }
    }

    public function composables()
    {
        return $this->belongsToMany(Composable::class, 'composable_ingredient');
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '<=', 5);
    }

    public function getFormattedStockAttribute()
    {
        return $this->stock . ' units';
    }

    public function setStockAttribute($value)
    {
        $this->attributes['stock'] = max(0, $value); // Ensure stock is not negative
    }

    public function isLowStock()
    {
        return $this->stock < $this->reorder_level;
    }
}
