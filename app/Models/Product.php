<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Models\Alert;
use App\Models\Traits\HasPrice;
use App\Models\Traits\HasStock;
use App\Traits\HasSlug;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
         'price',
        'cost',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'status' => Status::class,
        'is_featured' => 'boolean',
        'is_composable' => 'boolean',
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
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
            ->withPivot(['quantity', 'unit', 'cost_at_time'])
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

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function currentPrice()
    {
        return $this->morphOne(Price::class, 'priceable')->where('is_current', true);
    }

    public function stockLogs(): MorphMany
    {
        return $this->morphMany(StockLog::class, 'stockable');
    }

    public function alerts(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // Stock Management Methods
    public function adjustStock(float $quantity, string $reason = null, ?string $reference = null): void
    {
        DB::transaction(function () use ($quantity, $reason, $reference) {
            $oldQuantity = $this->stock_quantity;
            $this->stock_quantity = max(0, $this->stock_quantity + $quantity);
            $this->save();

            // Log stock movement
            $this->stockLogs()->create([
                'adjustment' => $quantity,
                'reason' => $reason ?? 'Manual Adjustment',
                'reference' => $reference,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $this->stock_quantity,
            ]);

            // Check stock levels and create alerts if necessary
            if ($this->isLowStock()) {
                $this->createStockAlert('low_stock');
            }

            // Update product status based on stock
            $this->updateAvailabilityStatus();
        });
    }

    public function setStockLevel(float $newQuantity, string $reason = null): void
    {
        $adjustment = $newQuantity - $this->stock_quantity;
        $this->adjustStock($adjustment, $reason ?? 'Stock Level Adjustment');
    }

    public function createStockAlert(string $type): void
    {
        if (!$this->hasActiveAlertOfType($type)) {
            $this->alerts()->create([
                'type' => $type,
                'message' => "Product {$this->name} is running low on stock. Current quantity: {$this->stock_quantity}",
                'status' => 'pending'
            ]);
        }
    }

    public function hasActiveAlertOfType(string $type): bool
    {
        return $this->alerts()
            ->where('type', $type)
            ->where('status', 'pending')
            ->exists();
    }

    public function updateAvailabilityStatus(): void
    {
        $isAvailable = $this->hasRequiredStock() && $this->hasRequiredIngredients();

        if ($this->status !== $isAvailable) {
            $this->status = $isAvailable;
            $this->save();

            Log::info("Product {$this->name} availability status updated to: " . ($isAvailable ? 'available' : 'unavailable'));
        }
    }

    public function hasRequiredStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function hasRequiredIngredients(): bool
    {
        if (!$this->ingredients()->exists()) {
            return true;
        }

        return $this->ingredients->every(function ($ingredient) {
            return $ingredient->stock_quantity >= ($ingredient->pivot->quantity ?? 0);
        });
    }

    // Price Management Methods
    public function updatePrice(float $newPrice, ?string $reason = null): void
    {
        DB::transaction(function () use ($newPrice, $reason) {
            // Deactivate current price
            $this->prices()->where('is_current', true)->update(['is_current' => false]);

            // Create new price record
            $this->prices()->create([
                'amount' => $newPrice,
                'previous_amount' => $this->getCurrentPrice(),
                'reason' => $reason,
                'is_current' => true,
                'effective_date' => now(),
            ]);

            // Update base price
            $this->price = $newPrice;
            $this->save();

            Log::info("Price updated for product {$this->name} to {$newPrice}");
        });
    }

    public function updateCost(float $newCost, ?string $reason = null): void
    {
        DB::transaction(function () use ($newCost, $reason) {
            $oldCost = $this->cost;
            $this->cost = $newCost;
            $this->save();

            $this->priceHistory()->create([
                'old_amount' => $oldCost,
                'new_amount' => $newCost,
                'type' => 'cost',
                'reason' => $reason,
            ]);

            Log::info("Cost updated for product {$this->name} from {$oldCost} to {$newCost}");
        });
    }

    // Analytics Methods
    public function getPerformanceMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $orderItems = $this->orderItems()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(DISTINCT order_id) as total_orders'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * price) as total_revenue'),
                DB::raw('SUM(quantity * cost) as total_cost'),
                DB::raw('COUNT(DISTINCT CASE WHEN created_at >= NOW() - INTERVAL 30 DAY THEN order_id END) as recent_orders')
            )
            ->first();

        $totalRevenue = $orderItems->total_revenue ?? 0;
        $totalCost = $orderItems->total_cost ?? 0;
        $profit = $totalRevenue - $totalCost;

        return [
            'total_orders' => $orderItems->total_orders ?? 0,
            'total_quantity' => $orderItems->total_quantity ?? 0,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'profit' => $profit,
            'profit_margin' => $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0,
            'recent_orders' => $orderItems->recent_orders ?? 0,
            'average_order_value' => $orderItems->total_orders > 0 ? $totalRevenue / $orderItems->total_orders : 0,
        ];
    }
    public function getTopProducts(Carbon $startDate, Carbon $endDate, int $limit = 5): Collection
    {
        return $this->products()
            ->select('products.*')
            ->selectRaw('COUNT(DISTINCT order_items.order_id) as total_orders')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
            ->selectRaw('SUM(order_items.quantity * order_items.cost) as total_cost')
            ->selectRaw('(SUM(order_items.quantity * order_items.price) - SUM(order_items.quantity * order_items.cost)) as profit')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('products.id')
            ->orderByDesc('total_revenue')
            ->take($limit)
            ->get();
    }
    
    public function calculateStockTurnoverRate(Carbon $startDate, Carbon $endDate): float
    {
        $metrics = $this->getPerformanceMetrics($startDate, $endDate);
        $avgStock = ($this->stock_quantity + $metrics['total_quantity']) / 2;

        if ($avgStock <= 0) {
            return 0;
        }

        $days = $startDate->diffInDays($endDate) ?: 1;
        return ($metrics['total_quantity'] / $avgStock) * (365 / $days);
    }

    
    public function calculateIngredientsCost(): float
    {
        return $this->ingredients()
            ->get()
            ->sum(function ($ingredient) {
                $quantity = $ingredient->pivot->quantity;
                $cost = $ingredient->currentPrice?->cost ?? $ingredient->cost_per_unit;
                return $quantity * $cost;
            });
    }

    public function updatePriceFromIngredients(float $markup = 1.4): void
    {
        $cost = $this->calculateIngredientsCost();
        $price = $cost * $markup;
        
        $this->updatePrice(
            newPrice: $price,
            // cost: $cost,
            reason: 'Automatic update based on ingredient costs'
        );
    }

    public function updatePopularityScore(): void
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        $score = $this->orderItems()
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('quantity');

        $this->popularity_score = $score;
        $this->save();
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where('stock_quantity', '>', 0)
            ->whereHas('ingredients', function ($query) {
                $query->where('stock_quantity', '>', DB::raw('product_ingredient.quantity'));
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
        return $query->orderByDesc('popularity_score');
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->where('status', true);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeWithStockAlert(Builder $query): Builder
    {
        return $query->whereHas('alerts', function ($query) {
            $query->where('type', 'low_stock')
                ->where('status', 'pending');
        });
    }

    public function scopeWithProfitMargin(Builder $query): Builder
    {
        return $query->addSelect([
            '*',
            'profit_margin' => DB::raw('((price - cost) / cost) * 100')
        ]);
    }

    // Computed Properties
    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->cost || $this->cost <= 0) {
                    return 0;
                }
                return (($this->price - $this->cost) / $this->cost) * 100;
            }
        );
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->stock_quantity <= 0) {
                    return 'out_of_stock';
                }
                if ($this->stock_quantity <= $this->reorder_point) {
                    return 'low_stock';
                }
                return 'in_stock';
            }
        );
    }

    // Helper Methods
    public function getRelatedProducts(int $limit = 5): Collection
    {
        return static::query()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->where('status', true)
            ->take($limit)
            ->get();
    }

    public function calculateTotalCost(): float
    {
        $ingredientsCost = $this->ingredients->sum(function ($ingredient) {
            return $ingredient->cost_per_unit * $ingredient->pivot->quantity;
        });

        $composablesCost = $this->composables->sum(function ($composable) {
            return $composable->cost * $composable->pivot->quantity;
        });

        return $ingredientsCost + $composablesCost;
    }

    public function needsReorder(): bool
    {
        return $this->status && $this->stock_quantity <= $this->reorder_point;
    }

    public function getReorderQuantitySuggestion(): float
    {
        $thirtyDaysAgo = now()->subDays(30);
        $averageDailyUsage = $this->orderItems()
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('quantity') / 30;

        return ceil($averageDailyUsage * 7); // 7 days of stock
    }
}