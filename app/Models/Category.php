<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CategoryType;
use App\Support\HasAdvancedFilter;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    // use SoftDeletes;
    use HasAdvancedFilter;
    use HasFactory;
    use HasSlug;


    protected const ATTRIBUTES = [
        'id',
        'name',
        'slug',
        'status',
        'is_composable',
        'parent_id',
        'type',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_composable',
        'status',
    ];

    protected $casts = [
        'is_composable' => 'boolean',
        'status' => 'boolean',
        'type' => CategoryType::class,
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeParentCategories(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForType(Builder $query, CategoryType $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeForProducts(Builder $query): Builder
    {
        return $query->forType(CategoryType::PRODUCT);
    }

    public function scopeForIngredients(Builder $query)
    {
        return $query->whereIn('type', [
            CategoryType::INGREDIENT->value,
            CategoryType::BASE->value,
            CategoryType::FRUIT->value
        ]);
    }

    public function scopeComposable(Builder $query): Builder
    {
        return $query->where('is_composable', true);
    }

    public function getItemsCountAttribute(): int
    {
        return match ($this->type) {
            Product::class => Product::where('category_id', $this->id)->count(),
            Ingredient::class => Ingredient::where('category_id', $this->id)->count(),
            default => 0
        };
    }

    public function isParentCategory(): bool
    {
        return null === $this->parent_id;
    }

    public function isProductCategory(): bool
    {
        return Product::class === $this->type;
    }

    public function isIngredientCategory(): bool
    {
        return Ingredient::class === $this->type;
    }

    public function canBeDeleted(): bool
    {
        return ! $this->hasChildren;
    }

    // Analytics Methods
    public function getProductStats(): array
    {
        if ( ! $this->isProductCategory()) {
            return [
                'total_products' => 0,
                'active_products' => 0,
                'total_orders' => 0,
                'low_stock_products' => 0,
                'revenue' => 0,
                'profit' => 0,
            ];
        }

        return Cache::remember("category_{$this->id}_stats", 3600, function () {
            $products = Product::where('category_id', $this->id)
                ->withCount(['orderItems as total_orders'])
                ->withSum('orderItems as revenue', 'total_price')
                ->withSum('orderItems as profit', 'profit')
                ->get();

            return [
                'total_products' => $products->count(),
                'active_products' => $products->where('status', true)->count(),
                'total_orders' => $products->sum('total_orders'),
                'low_stock_products' => $products->filter->isLowStock()->count(),
                'revenue' => $products->sum('revenue'),
                'profit' => $products->sum('profit'),
            ];
        });
    }

    public function getIngredientStats(): array
    {
        if ( ! $this->isIngredientCategory()) {
            return [
                'total_ingredients' => 0,
                'active_ingredients' => 0,
                'low_stock_ingredients' => 0,
                'expiring_soon' => 0,
                'total_cost' => 0,
                'avg_usage' => 0,
            ];
        }

        return Cache::remember("category_{$this->id}_stats", 3600, function () {
            $ingredients = Ingredient::where('category_id', $this->id)->get();

            return [
                'total_ingredients' => $ingredients->count(),
                'active_ingredients' => $ingredients->where('status', true)->count(),
                'low_stock_ingredients' => $ingredients->filter->isLowStock()->count(),
                'expiring_soon' => $ingredients->filter->isExpiringSoon()->count(),
                'total_cost' => $ingredients->sum(fn ($i) => $i->cost * $i->stock_quantity),
                'avg_usage' => $ingredients->avg('average_daily_usage'),
            ];
        });
    }

    public function getIngredientsStats(): array
    {
        return Cache::remember("category_{$this->id}_stats", 3600, function () {
            $ingredients = $this->ingredients;

            return [
                'total' => $ingredients->count(),
                'active' => $ingredients->where('status', true)->count(),
                'low_stock' => $ingredients->filter->isLowStock()->count(),
                'out_of_stock' => $ingredients->where('stock_quantity', 0)->count(),
                'total_value' => $ingredients->sum(fn ($i) => $i->stock_quantity * $i->cost)
            ];
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    public function composableConfiguration(): HasOne
    {
        return $this->hasOne(ComposableConfiguration::class);
    }

    public function portionConfiguration(): HasOne
    {
        return $this->hasOne(PortionConfiguration::class);
    }

    public function isComposable(): bool
    {
        return $this->is_composable && $this->composableConfiguration?->is_active;
    }

    public function getComposableFeatures(): array
    {
        if (!$this->composableConfiguration) {
            return [];
        }

        $features = [];

        if ($this->composableConfiguration->has_base) {
            $features['base'] = $this->composableConfiguration->getAvailableBaseTypes();
        }

        if ($this->composableConfiguration->has_sugar) {
            $features['sugar'] = $this->composableConfiguration->getAvailableSugarTypes();
        }

        if ($this->composableConfiguration->has_size) {
            $features['size'] = $this->composableConfiguration->getAvailableSizes();
        }

        if ($this->composableConfiguration->has_addons) {
            $features['addons'] = $this->composableConfiguration->getAvailableAddonTypes();
        }

        return $features;
    }

    public function getPortionOptions(): array
    {
        if (!$this->portionConfiguration) {
            return [];
        }

        return [
            'sizes' => $this->portionConfiguration->getAvailableSizes(),
            'addons' => $this->portionConfiguration->getAvailableAddons(),
            'sides' => $this->portionConfiguration->getAvailableSides(),
            'upgrades' => $this->portionConfiguration->getAvailableUpgrades(),
        ];
    }
}
