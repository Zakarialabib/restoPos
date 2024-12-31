<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasPrice;
use App\Models\Traits\HasStock;
use App\Traits\HasSlug;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasPrice;
    use HasSlug;
    use HasStock;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'category_id',
        'image',
        'status',
        'is_featured',
        'recipe_id',
        'is_composable',
        'nutritional_info',
        'min_stock',
        'stock_quantity',
        'reorder_point',
        'deleted_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'nutritional_info' => 'array',
        'min_stock' => 'integer',
        'stock_quantity' => 'float',
        'reorder_point' => 'float',
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

    public function prices(): MorphMany // Changed from HasMany to MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function currentPrice()
    {
        return $this->morphOne(Price::class, 'priceable')->where('is_current', true); // Use morphOne for single current price
    }


    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    public function activeStockAlert()
    {
        return $this->alerts()->where('status', 'pending')->latest();
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', true)
            ->whereHas('ingredients', function ($query): void {
                $query->where('stock_quantity', '>', 0);
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCategory(Builder $query, int|array $categoryIds): Builder
    {
        return $query->whereIn('category_id', (array) $categoryIds);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('orderItems')
            ->orderByDesc('order_items_count');
    }

    // Stock Management
    public function updateAvailabilityStatus(): void
    {
        $isAvailable = $this->hasRequiredIngredients();

        if ($this->status !== $isAvailable) {
            $this->status = $isAvailable;
            $this->save();
        }
    }

    public function hasRequiredIngredients(): bool
    {
        return $this->ingredients->every(
            fn ($ingredient) => $ingredient->hasEnoughStock($ingredient->pivot->quantity)
        );
    }

    public function consumeIngredients(int $quantity): void
    {
        if ( ! $this->hasEnoughIngredients($quantity)) {
            throw new Exception("Insufficient ingredients for product: {$this->name}");
        }

        DB::transaction(function () use ($quantity): void {
            foreach ($this->ingredients as $ingredient) {
                $ingredient->decrementStock($ingredient->pivot->quantity * $quantity);
            }
        });
    }

    // Cost Calculations
    public function calculateCost(): float
    {
        return $this->ingredients->sum(
            fn ($ingredient) => $ingredient->cost * $ingredient->pivot->quantity
        );
    }

    // Nutritional Information
    public function calculateNutritionalInfo(): array
    {
        return $this->ingredients->reduce(function (array $total, Ingredient $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $nutrition = $ingredient->calculateNutritionalInfo($quantity);

            return [
                'calories' => $total['calories'] + ($nutrition['calories'] ?? 0),
                'protein' => $total['protein'] + ($nutrition['protein'] ?? 0),
                'carbs' => $total['carbs'] + ($nutrition['carbs'] ?? 0),
                'fat' => $total['fat'] + ($nutrition['fat'] ?? 0),
            ];
        }, ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0]);
    }

    // Scope for filtering by category
    public function scopeFilterByCategory(Builder $query, ?int $categoryId): Builder
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }

        return $query;
    }

    // Method to retrieve the current active price
    public function getCurrentActivePrice(): ?Price
    {
        return $this->prices()->active()->first();
    }

    public function getActivePrice(): ?float
    {
        return $this->getCurrentPrice()?->amount;
    }

    public function getProfitMarginPercentage(): ?float
    {
        $price = $this->getActivePrice();
        $cost = $this->calculateCost();

        if ( ! $price || ! $cost) {
            return null;
        }

        return round((($price - $cost) / $cost) * 100, 2);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    public function isOutOfStock(): bool
    {
        return 0 === $this->stock_quantity;
    }

    // Attributes
    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function (): ?float {
                $currentPrice = $this->getCurrentPrice();
                $cost = $this->calculateCost();

                if ( ! $currentPrice || ! $cost) {
                    return null;
                }

                return (($currentPrice - $cost) / $cost) * 100;
            }
        );
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->isLowStock()) {
                    return 'low';
                }
                return $this->getCurrentStock() > 0 ? 'in_stock' : 'out_of_stock';
            }
        );
    }

    // getPriceForSizeAndUnit
    public function getPriceForSizeAndUnit(string $size, string $unit): ?Price
    {
        return $this->prices()->where('size', $size)->where('unit', $unit)->first();
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice?->amount;
    }

    public function validateStockQuantity(float $quantity): bool
    {
        if ($quantity < 0) {
            return false;
        }

        if (!$this->is_composable) {
            return $this->stock_quantity >= $quantity;
        }

        return $this->ingredients->every(function ($ingredient) use ($quantity) {
            $requiredQuantity = $ingredient->pivot->quantity * $quantity;
            return $ingredient->stock_quantity >= $requiredQuantity;
        });
    }

}
