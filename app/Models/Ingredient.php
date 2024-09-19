<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 * Represents an ingredient in the inventory.
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'composable_juice_id'
    ];

    // Define the inverse relationship with ComposableJuice
    public function composableJuice()
    {
        return $this->belongsTo(ComposableJuice::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot('quantity');
    }

}
