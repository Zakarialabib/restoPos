<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeWithProductCount(Builder $query): Builder
    {
        return $query->withCount('products');
    }
}
