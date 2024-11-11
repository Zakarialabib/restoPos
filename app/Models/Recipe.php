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

    public function calculateNutritionalInfo(): array
    {
        return $this->ingredients->reduce(function ($carry, $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $nutritionalInfo = $ingredient->calculateNutritionalInfo($quantity);

            return [
                'calories' => $carry['calories'] + $nutritionalInfo['calories'],
                'protein' => $carry['protein'] + $nutritionalInfo['protein'],
                'carbs' => $carry['carbs'] + $nutritionalInfo['carbs'],
                'fat' => $carry['fat'] + $nutritionalInfo['fat'],
            ];
        }, ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0]);
    }

    public function calculateTotalNutritionalInfo(): array
    {
        return $this->calculateNutritionalInfo();
    }
}
