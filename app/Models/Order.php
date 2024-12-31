<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'status' => OrderStatus::class,
    ];

    // Add order analytics methods
    public function getTotalRevenueAttribute(): float
    {
        return $this->items->sum(fn ($item) => $item->price * $item->quantity);
    }

    public function getProfit(): float
    {
        return $this->items->sum(fn ($item) => ($item->price - $item->cost) * $item->quantity);
    }

    // Add order statistics scopes
    public function scopeRevenueBetween(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatus::Completed)
            ->selectRaw('SUM(total_amount) as revenue');
    }

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Pending);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Completed);
    }

    public function scopeByPhone(Builder $query, string $phone): Builder
    {
        return $query->where('customer_phone', $phone);
    }

    public function updateStatus(OrderStatus $status): void
    {
        $this->status = $status;
        $this->save();
        // Additional logic for updating inventory or notifying users can be added here
    }


    // public function calculateTotal(): void
    // {
    //     $this->total_amount = $this->items->sum(fn ($item) => $item->price * $item->quantity);
    //     $this->save();
    // }

    // public function addItem(array $itemData): OrderItem
    // {
    //     $item = $this->items()->create($itemData);
    //     $this->calculateTotal();
    //     return $item;
    // }

    public function processOrderIngredients(): bool
    {
        DB::beginTransaction();
        try {
            foreach ($this->items as $orderItem) {
                $product = $orderItem->product;

                // Check ingredient availability
                $isAvailable = $this->checkIngredientAvailability($product, $orderItem->quantity);

                if ( ! $isAvailable) {
                    DB::rollBack();
                    return false;
                }

                // Reduce ingredient stocks
                $this->reduceIngredientStocks($product, $orderItem->quantity);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            // Log the error
            Log::error('Order Processing Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Relationship with User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add an item to the order.
     *
     * @param array $itemData
     * @return void
     * @throws Exception
     */
    public function addItem(array $itemData): void
    {
        DB::transaction(function () use ($itemData): void {
            $orderItem = $this->items()->create([
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
                'details' => $itemData['details'] ?? [],
            ]);

            // Additional logic such as reducing stock
            $product = Product::findOrFail($itemData['product_id']);
            $product->reduceStock($itemData['quantity']);
        });
    }

    /**
     * Remove an item from the order.
     *
     * @param int $orderItemId
     * @return void
     */
    public function removeItem(int $orderItemId): void
    {
        $orderItem = $this->items()->findOrFail($orderItemId);

        DB::transaction(function () use ($orderItem): void {
            $orderItem->delete();

            // Additional logic such as restoring stock
            $product = Product::findOrFail($orderItem->product_id);
            $product->restoreStock($orderItem->quantity);
        });
    }

    /**
     * Calculate the total amount for the order.
     *
     * @return float
     */
    public function calculateTotal(): float
    {
        return $this->items->sum(fn (OrderItem $item) => $item->price * $item->quantity);
    }

    /**
     * Add multiple items to the order at once
     */
    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        $this->calculateTotal();
    }

    /**
     * Update item quantity
     */
    public function updateItemQuantity(int $orderItemId, int $quantity): void
    {
        $item = $this->items()->find($orderItemId);
        if ($item) {
            $item->updateQuantity($quantity);
            $this->calculateTotal();
        }
    }

    /**
     * Check if order can be modified
     */
    public function canBeModified(): bool
    {
        return in_array($this->status, [
            OrderStatus::Pending,
            OrderStatus::Processing
        ]);
    }

    private function checkIngredientAvailability(Product $product, int $quantity): bool
    {
        foreach ($product->ingredients as $ingredient) {
            if ( ! $ingredient->isStockSufficientForProduct($product, $quantity)) {
                return false;
            }
        }
        return true;
    }

    private function reduceIngredientStocks(Product $product, int $quantity): void
    {
        foreach ($product->ingredients as $ingredient) {
            $ingredient->reduceStockForProduct($product, $quantity);
        }
    }
}
