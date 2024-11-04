<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'category_id',
        'image',
        'is_available',
        'is_featured',
        'recipe_id',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot(['stock'])
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function composables(): BelongsToMany
    {
        return $this->belongsToMany(Composable::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCategory(Builder $query, int|array $categoryIds): Builder
    {
        return $query->whereIn('category_id', (array) $categoryIds);
    }

    // Attributes
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Number::format($value, locale: 'fr_MA'),
        );
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->stock <= 0) {
                    return 'Out of Stock';
                }
                return 'In Stock';
            }
        );
    }
}
