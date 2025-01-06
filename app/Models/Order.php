<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        'payment_status',
        'payment_method',
        'notes',
        'discount_amount',
        'tax_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    // Analytics Methods
    public function getTotalRevenueAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function getNetRevenueAttribute(): float
    {
        return $this->total_revenue - ($this->discount_amount + $this->refunded_amount);
    }

    public function getProfit(): float
    {
        $itemsProfit = $this->items->sum(fn($item) => ($item->price - $item->cost) * $item->quantity);
        return $itemsProfit - ($this->discount_amount + $this->refunded_amount);
    }

    public function getProfitMargin(): float
    {
        if ($this->total_revenue <= 0) {
            return 0;
        }
        return ($this->getProfit() / $this->total_revenue) * 100;
    }

    // Payment Methods
    public function updatePaymentStatus(PaymentStatus $status): void
    {
        if (!$this->payment_status->canTransitionTo($status)) {
            throw new Exception("Cannot transition from {$this->payment_status->value} to {$status->value}");
        }

        DB::transaction(function () use ($status) {
            $this->payment_status = $status;
            $this->save();

            // Log the payment status change
            Log::info("Order #{$this->id} payment status updated to {$status->value}");
        });
    }

    public function processRefund(float $amount, string $reason = null): void
    {
        if ($amount > $this->total_amount - $this->refunded_amount) {
            throw new Exception('Refund amount cannot exceed remaining order amount');
        }

        DB::transaction(function () use ($amount, $reason) {
            $this->refunded_amount += $amount;
            $this->payment_status = PaymentStatus::Refunded;
            $this->save();

            // Log the refund
            Log::info("Order #{$this->id} refunded amount: {$amount}, reason: {$reason}");
        });
    }

    public function applyDiscount(float $amount, string $reason = null): void
    {
        if ($amount > $this->total_amount) {
            throw new Exception('Discount amount cannot exceed order total');
        }

        DB::transaction(function () use ($amount, $reason) {
            $this->discount_amount = $amount;
            $this->save();

            // Log the discount
            Log::info("Order #{$this->id} discount applied: {$amount}, reason: {$reason}");
        });
    }

    // Payment Verification Methods
    public function isPaymentVerificationRequired(): bool
    {
        return $this->payment_method?->requiresVerification() ?? false;
    }

    public function getEstimatedProcessingTime(): string
    {
        return $this->payment_method?->processingTime() ?? 'Unknown';
    }

    // Scopes
    public function scopePaidOrders(Builder $query): Builder
    {
        return $query->where('payment_status', PaymentStatus::Completed->value);
    }

    public function scopePendingPayments(Builder $query): Builder
    {
        return $query->where('payment_status', PaymentStatus::Pending->value);
    }

    public function scopeByPaymentMethod(Builder $query, PaymentMethod $method): Builder
    {
        return $query->where('payment_method', $method->value);
    }

    public function scopeRevenueBetween(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->where('status', OrderStatus::Completed)
            ->where('payment_status', PaymentStatus::Completed);
    }

    public function scopeProfitableOrders(Builder $query, float $minProfitMargin = 0): Builder
    {
        return $query->whereHas('items', function ($query) use ($minProfitMargin) {
            $query->whereRaw('(price - cost) / price * 100 >= ?', [$minProfitMargin]);
        });
    }

    public function scopeRefunded(Builder $query): Builder
    {
        return $query->where('payment_status', PaymentStatus::Refunded->value)
            ->where('refunded_amount', '>', 0);
    }

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Status Management
    public function updateStatus(OrderStatus $status): void
    {
        DB::transaction(function () use ($status) {
            $this->status = $status;
            $this->save();

            // Log status change
            Log::info("Order #{$this->id} status updated to {$status->value}");

            // Notify relevant parties
            // event(new OrderStatusChanged($this));
        });
    }

    // Inventory Management
    public function processOrderIngredients(): bool
    {
        DB::beginTransaction();
        try {
            foreach ($this->items as $orderItem) {
                $product = $orderItem->product;
                if (!$this->checkIngredientAvailability($product, $orderItem->quantity)) {
                    DB::rollBack();
                    return false;
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order Processing Failed: ' . $e->getMessage());
            return false;
        }
    }

    private function checkIngredientAvailability(Product $product, int $quantity): bool
    {
        foreach ($product->ingredients as $ingredient) {
            if (!$ingredient->isStockSufficientForProduct($product, $quantity)) {
                return false;
            }
        }
        return true;
    }

    // Order Item Management
    public function addItem(array $itemData): void
    {
        DB::transaction(function () use ($itemData): void {
            $orderItem = $this->items()->create([
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
                'details' => $itemData['details'] ?? [],
            ]);

            $product = Product::findOrFail($itemData['product_id']);
            $product->reduceStock($itemData['quantity']);

            $this->recalculateTotal();
        });
    }

    public function removeItem(int $orderItemId): void
    {
        DB::transaction(function () use ($orderItemId): void {
            $orderItem = $this->items()->findOrFail($orderItemId);
            $product = Product::findOrFail($orderItem->product_id);
            
            $product->increaseStock($orderItem->quantity);
            $orderItem->delete();

            $this->recalculateTotal();
        });
    }

    public function updateItemQuantity(int $orderItemId, int $quantity): void
    {
        DB::transaction(function () use ($orderItemId, $quantity): void {
            $item = $this->items()->findOrFail($orderItemId);
            $product = Product::findOrFail($item->product_id);

            // Adjust stock
            if ($quantity > $item->quantity) {
                $product->reduceStock($quantity - $item->quantity);
            } else {
                $product->increaseStock($item->quantity - $quantity);
            }

            $item->quantity = $quantity;
            $item->save();

            $this->recalculateTotal();
        });
    }

    private function recalculateTotal(): void
    {
        $this->total_amount = $this->calculateTotal();
        $this->save();
    }

    private function calculateTotal(): float
    {
        $subtotal = $this->items->sum(fn(OrderItem $item) => $item->price * $item->quantity);
        return $subtotal - $this->discount_amount + $this->tax_amount;
    }

    public function canBeModified(): bool
    {
        return in_array($this->status, [
            OrderStatus::Pending,
            OrderStatus::Processing
        ]);
    }
}
