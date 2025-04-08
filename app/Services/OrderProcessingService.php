<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderProcessingService
{
    public function processPayment(Order $order, array $paymentData): void
    {
        DB::transaction(function () use ($order, $paymentData): void {
            $order->payment_method = $paymentData['method'];
            $order->payment_status = PaymentStatus::Completed;
            $order->payment_details = $paymentData;
            $order->paid_at = now();
            $order->save();
        });
    }

    public function processRefund(Order $order, float $amount, ?string $reason = null): void
    {
        if ($amount > $order->total_amount - $order->refunded_amount) {
            throw new Exception('Refund amount cannot exceed remaining order amount');
        }

        DB::transaction(function () use ($order, $amount, $reason): void {
            $order->refunded_amount += $amount;
            $order->payment_status = PaymentStatus::Refunded;
            $order->refund_reason = $reason;
            $order->refunded_at = now();
            $order->save();

            // Restore stock for refunded items if needed
            if (OrderStatus::Completed === $order->status) {
                foreach ($order->items as $item) {
                    $item->product->adjustStock(
                        $item->quantity,
                        'Order Refunded: ' . $order->id
                    );
                }
            }
        });
    }

    public function applyDiscount(Order $order, float $amount, string $type = 'fixed', ?string $reason = null): void
    {
        $discountAmount = 'percentage' === $type
            ? ($order->total_amount * ($amount / 100))
            : $amount;

        if ($discountAmount > $order->total_amount) {
            throw new Exception('Discount amount cannot exceed order total');
        }

        DB::transaction(function () use ($order, $discountAmount, $type, $reason): void {
            $order->discount_amount = $discountAmount;
            $order->discount_type = $type;
            $order->discount_reason = $reason;
            $order->final_amount = $this->calculateFinalAmount($order);
            $order->save();
        });
    }

    public function addOrderItem(Order $order, array $itemData): OrderItem
    {
        return DB::transaction(function () use ($order, $itemData) {
            $product = Product::findOrFail($itemData['product_id']);

            if ( ! $product->hasRequiredStock()) {
                throw new Exception('Product is out of stock');
            }

            $orderItem = $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'] ?? $product->price,
                'cost' => $product->cost,
                'notes' => $itemData['notes'] ?? null,
                'customizations' => $itemData['customizations'] ?? null,
            ]);

            // Adjust stock
            $product->adjustStock(
                -$itemData['quantity'],
                'Order Item Added: ' . $order->id
            );

            // Recalculate order totals
            $this->recalculateOrderTotals($order);

            return $orderItem;
        });
    }

    public function removeOrderItem(Order $order, int $orderItemId): void
    {
        DB::transaction(function () use ($order, $orderItemId): void {
            $item = $order->items()->findOrFail($orderItemId);

            // Restore stock
            $item->product->adjustStock(
                $item->quantity,
                'Order Item Removed: ' . $order->id
            );

            $item->delete();

            // Recalculate order totals
            $this->recalculateOrderTotals($order);
        });
    }

    public function updateItemQuantity(Order $order, int $orderItemId, int $newQuantity): void
    {
        if ($newQuantity <= 0) {
            throw new Exception('Quantity must be greater than zero');
        }

        DB::transaction(function () use ($order, $orderItemId, $newQuantity): void {
            $item = $order->items()->findOrFail($orderItemId);
            $quantityDiff = $newQuantity - $item->quantity;

            // Check stock availability for increase
            if ($quantityDiff > 0 && ! $item->product->hasRequiredStock()) {
                throw new Exception('Not enough stock available');
            }

            // Adjust stock
            $item->product->adjustStock(
                -$quantityDiff,
                'Order Item Quantity Updated: ' . $order->id
            );

            $item->quantity = $newQuantity;
            $item->save();

            // Recalculate order totals
            $this->recalculateOrderTotals($order);
        });
    }

    public function markAsPaid(Order $order): void
    {
        if ($order->is_paid) {
            throw new Exception(__('Order is already marked as paid.'));
        }

        DB::transaction(function () use ($order): void {
            $order->is_paid = true;
            $order->paid_at = now();
            $order->save();
        });
    }

    public function updateStatus(Order $order, OrderStatus $status): void
    {
        if ($order->status === $status) {
            throw new Exception(__('Order is already in this status.'));
        }

        DB::transaction(function () use ($order, $status): void {
            $order->status = $status;
            $order->save();
        });
    }

    public function bulkUpdateStatus(array $orderIds, OrderStatus $status): void
    {
        DB::transaction(function () use ($orderIds, $status): void {
            Order::whereIn('id', $orderIds)
                ->update(['status' => $status]);
        });
    }

    public function bulkMarkAsPaid(array $orderIds): void
    {
        DB::transaction(function () use ($orderIds): void {
            Order::whereIn('id', $orderIds)
                ->where('is_paid', false)
                ->update([
                    'is_paid' => true,
                    'paid_at' => now()
                ]);
        });
    }

    private function recalculateOrderTotals(Order $order): void
    {
        $totalAmount = $order->items->sum(fn ($item) => $item->price * $item->quantity);

        $taxAmount = $totalAmount * (config('restaurant.tax_rate', 0.1));

        $order->update([
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'final_amount' => $this->calculateFinalAmount($order),
        ]);
    }

    private function calculateFinalAmount(Order $order): float
    {
        return $order->total_amount + $order->tax_amount - $order->discount_amount;
    }
}
