<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use App\Traits\HasInventory;
use App\Traits\HasPricing;
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
    use HasAdvancedFilter;
    use HasInventory;
    use HasPricing;
    use HasSlug;
    use HasUuids;
    use SoftDeletes;

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
    public function scopeActive($query)
    {
        return $query->where('status', Status::AVAILABLE);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSeasonal($query)
    {
        return $query->where('is_seasonal', true);
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('popularity');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearchable($query, $search)
    {
        return $query->where(function ($q) use ($search): void {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%");
                });
        });
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
        if ( ! $this->status || $this->stock_quantity <= 0) {
            return false;
        }

        if ($this->ingredients()->exists()) {
            return $this->ingredients->every(fn ($ingredient) => $ingredient->stock_quantity >= ($ingredient->pivot->quantity ?? 0));
        }

        return true;
    }

    public function calculateIngredientsCost(): float
    {
        if ( ! $this->ingredients()->exists()) {
            return 0;
        }

        return $this->ingredients->sum(fn ($ingredient) => $ingredient->cost * ($ingredient->pivot->quantity ?? 0));
    }

    public function getRequiredIngredients(): Collection
    {
        return $this->ingredients->map(fn ($ingredient) => [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'type' => $ingredient->type,
            'required_quantity' => $ingredient->pivot->quantity,
            'unit' => $ingredient->pivot->unit,
            'available_quantity' => $ingredient->stock_quantity,
            'is_available' => $ingredient->stock_quantity >= $ingredient->pivot->quantity,
            'is_seasonal' => $ingredient->is_seasonal,
        ]);
    }

    public function scopeWithIngredients($query, array $ingredientIds)
    {
        if (empty($ingredientIds)) {
            return $query;
        }

        return $query->whereHas('ingredients', function ($q) use ($ingredientIds): void {
            $q->whereIn('ingredients.id', $ingredientIds);
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeSearch($query, ?string $search)
    {
        return $search
            ? $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            : $query;
    }
}
