<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasInventory;
use App\Models\Traits\HasPricing;
use App\Support\HasAdvancedFilter;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasInventory;
    use HasPricing;
    use SoftDeletes;
    use HasUuids;
    use HasSlug;
    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'slug',
        'description',
        'category_id',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'image',
        'status',
        'is_featured',
        'is_composable',
        'stock_quantity',
        'reorder_point',
        'cost',
        'price',
        'stock_status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_composable' => 'boolean',
        'stock_quantity' => 'float',
        'reorder_point' => 'float',
        'cost' => 'float',
        'price' => 'float',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeComposable($query)
    {
        return $query->where('is_composable', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', true)
            ->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', DB::raw('reorder_point'));
    }

    // Helper Methods
    public function isAvailableForOrder(): bool
    {
        if (!$this->status || $this->stock_quantity <= 0) {
            return false;
        }

        if ($this->ingredients()->exists()) {
            return $this->ingredients->every(function ($ingredient) {
                return $ingredient->stock_quantity >= ($ingredient->pivot->quantity ?? 0);
            });
        }

        return true;
    }

    public function calculateIngredientsCost(): float
    {
        if (!$this->ingredients()->exists()) {
            return 0;
        }

        return $this->ingredients->sum(function ($ingredient) {
            return $ingredient->cost * ($ingredient->pivot->quantity ?? 0);
        });
    }

    public function getRequiredIngredients(): Collection
    {
        return $this->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'required_quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit,
                'available_quantity' => $ingredient->stock_quantity,
                'is_available' => $ingredient->stock_quantity >= $ingredient->pivot->quantity,
            ];
        });
    }
}
