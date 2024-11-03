<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // Add order validation
    public function validate(): bool
    {
        // Check stock availability
        foreach ($this->items as $item) {
            if ( ! $item->product->hasAvailableStock($item->quantity)) {
                return false;
            }
        }

        // Check if all required ingredients are available
        foreach ($this->items as $item) {
            foreach ($item->product->ingredients as $ingredient) {
                if ( ! $ingredient->hasEnoughStock($ingredient->pivot->quantity * $item->quantity)) {
                    return false;
                }
            }
        }

        return true;
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
        DB::transaction(function () use ($status): void {
            $this->status = $status;
            $this->save();
        });
    }

    public function updateInventory(): bool
    {
        try {
            DB::transaction(function (): void {
                foreach ($this->items as $item) {
                    $product = $item->product;
                    if ( ! $product) {
                        continue;
                    }

                    foreach ($product->ingredients as $ingredient) {
                        $requiredQuantity = $ingredient->pivot->stock * $item->quantity;

                        if ( ! $ingredient->hasEnoughStock($requiredQuantity)) {
                            throw new Exception("Insufficient stock for ingredient: {$ingredient->name}");
                        }

                        $ingredient->updateStock(-$requiredQuantity);
                    }
                }
            });

            return true;
        } catch (Exception $e) {
            Log::error('Order inventory update failed: ' . $e->getMessage());
            return false;
        }
    }


    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum(fn ($item) => $item->price * $item->quantity);
        $this->save();
    }

    public function addItem(array $itemData): OrderItem
    {
        $item = $this->items()->create($itemData);
        $this->calculateTotal();
        return $item;
    }
}
