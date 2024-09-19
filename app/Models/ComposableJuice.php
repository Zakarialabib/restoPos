<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposableJuice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'ingredients'
    ];

    // Define the relationship with the Ingredient model
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' DH'; // Format price with currency
    }
}
