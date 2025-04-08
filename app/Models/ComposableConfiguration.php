<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComposableConfiguration extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'has_base',
        'has_sugar',
        'has_size',
        'has_addons',
        'min_ingredients',
        'max_ingredients',
        'sizes',
        'base_types',
        'sugar_types',
        'addon_types',
        'icons',
        'is_active',
    ];

    protected $casts = [
        'has_base' => 'boolean',
        'has_sugar' => 'boolean',
        'has_size' => 'boolean',
        'has_addons' => 'boolean',
        'min_ingredients' => 'integer',
        'max_ingredients' => 'integer',
        'sizes' => 'array',
        'base_types' => 'array',
        'sugar_types' => 'array',
        'addon_types' => 'array',
        'icons' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    public function getAvailableOptions(string $type): array
    {
        return match($type) {
            'sizes' => $this->sizes ?? [],
            'base_types' => $this->base_types ?? [],
            'sugar_types' => $this->sugar_types ?? [],
            'addon_types' => $this->addon_types ?? [],
            default => [],
        };
    }

    public function validateOption(string $type, string $value): bool
    {
        return in_array($value, $this->getAvailableOptions($type));
    }

    public function getIcon(string $type): string
    {
        return $this->icons[$type] ?? '';
    }

    public function validateIngredientsCount(int $count): bool
    {
        if ($count < $this->min_ingredients) {
            return false;
        }

        if ($this->max_ingredients !== null && $count > $this->max_ingredients) {
            return false;
        }

        return true;
    }

    public function getSizeMultiplier(string $size): float
    {
        return match($size) {
            'small' => 0.8,
            'medium' => 1.0,
            'large' => 1.4,
            'x-large' => 1.8,
            default => 1.0,
        };
    }
}
