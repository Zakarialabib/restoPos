<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'description',
        'instructions',
        'preparation_time',
        'type',
        'is_featured',
        'nutritional_info'
    ];

    protected $casts = [
        'instructions' => 'json',
        'nutritional_info' => 'json'
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')
            ->withPivot(['quantity', 'unit', 'preparation_notes'])
            ->withTimestamps();
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
