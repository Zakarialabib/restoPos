<?php


declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService
{
    public function calculateTotalRevenue(?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->sum('total_amount');
    }

    public function calculateTotalProfit(?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->sum('total_amount');
    }

    public function calculateAverageOrderValue(?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->avg('total_amount') ?? 0;
    }

    public function getOrderCount(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->count();
    }

    public function getTotalCustomers(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->distinct('customer_name')
            ->count('customer_name');
    }

    public function getRepeatCustomers(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        return DB::table('orders')
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->select('customer_name')
            ->groupBy('customer_name')
            ->havingRaw('COUNT(*) > 1')
            ->count();
    }

    public function getTopCustomers(int $limit = 10): Collection
    {
        return Order::query()
            ->where('status', OrderStatus::Completed)
            ->select(
                'customer_name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as total_spent')
            )
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }

    public function getDailyOrders(?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getDailyRevenue(?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getDailyProfit(?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return Order::query()
            ->when(
                $startDate && $endDate,
                fn ($query) =>
                $query->whereBetween('created_at', [$startDate, $endDate])
            )
            ->where('status', OrderStatus::Completed)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as profit')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getOrderStatistics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_orders' => $this->getOrderCount($startDate, $endDate),
            'total_revenue' => $this->calculateTotalRevenue($startDate, $endDate),
            'total_profit' => $this->calculateTotalProfit($startDate, $endDate),
            'average_order_value' => $this->calculateAverageOrderValue($startDate, $endDate),
            'daily_orders' => $this->getDailyOrders($startDate, $endDate),
            'daily_revenue' => $this->getDailyRevenue($startDate, $endDate),
            'daily_profit' => $this->getDailyProfit($startDate, $endDate)
        ];
    }

    public function getPopularItems(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', OrderStatus::Completed)
            ->select(
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('SUM(order_items.quantity * (order_items.price - order_items.cost)) as total_profit')
            )
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    public function updateOrderStatus(Order $order, OrderStatus $newStatus): void
    {
        if ( ! $this->validateStatusTransition($order, $newStatus)) {
            throw new InvalidArgumentException("Invalid status transition from {$order->status->value} to {$newStatus->value}");
        }

        $order->status = $newStatus;
        $order->save();
    }

    public function validateStatusTransition(Order $order, OrderStatus $newStatus): bool
    {
        $allowedTransitions = [
            OrderStatus::Pending->value => [OrderStatus::Preparing, OrderStatus::Cancelled],
            OrderStatus::Preparing->value => [OrderStatus::Completed, OrderStatus::Cancelled],
            OrderStatus::Completed->value => [OrderStatus::Refunded],
            OrderStatus::Cancelled->value => [],
            OrderStatus::Refunded->value => [],
        ];

        return in_array($newStatus, $allowedTransitions[$order->status->value] ?? []);
    }

    public function getAvailableStatuses(Order $order): array
    {
        $allowedTransitions = [
            OrderStatus::Pending->value => [OrderStatus::Preparing, OrderStatus::Cancelled],
            OrderStatus::Preparing->value => [OrderStatus::Completed, OrderStatus::Cancelled],
            OrderStatus::Completed->value => [OrderStatus::Refunded],
            OrderStatus::Cancelled->value => [],
            OrderStatus::Refunded->value => [],
        ];

        return $allowedTransitions[$order->status->value] ?? [];
    }

    public function processPayment(Order $order, array $paymentData): void
    {
        // Implement payment processing logic
        $order->payment_status = PaymentStatus::Completed;
        $order->paid_amount = $order->total_amount;
        $order->payment_date = now();
        $order->payment_method = $paymentData['method'];
        $order->payment_reference = $paymentData['reference'];
        $order->save();
    }

    public function processRefund(Order $order, float $amount, ?string $reason = null): void
    {
        if ( ! $order->canBeRefunded()) {
            throw new InvalidArgumentException("Order cannot be refunded");
        }

        if ($amount > $order->getRemainingRefundableAmount()) {
            throw new InvalidArgumentException("Refund amount exceeds refundable amount");
        }

        // Implement refund processing logic
        $order->refunded_amount += $amount;
        $order->status = $order->isFullyRefunded() ? OrderStatus::Refunded : $order->status;
        $order->save();
    }

    public function applyDiscount(Order $order, float $amount, string $type = 'fixed', ?string $reason = null): void
    {
        if ( ! $order->canBeModified()) {
            throw new InvalidArgumentException("Order cannot be modified");
        }

        $discount = 'percentage' === $type
            ? ($order->total_amount * $amount / 100)
            : $amount;

        $order->discount_amount = $discount;
        $order->discount_type = $type;
        $order->discount_reason = $reason;
        $order->save();
    }

    public function addOrderItem(Order $order, array $itemData): OrderItem
    {
        if ( ! $order->canBeModified()) {
            throw new InvalidArgumentException("Order cannot be modified");
        }

        return $order->items()->create($itemData);
    }

    public function removeOrderItem(Order $order, int $orderItemId): void
    {
        if ( ! $order->canBeModified()) {
            throw new InvalidArgumentException("Order cannot be modified");
        }

        $order->items()->findOrFail($orderItemId)->delete();
    }

    // deleteOrder

    public function deleteOrder(Order $order): void
    {
        if (OrderStatus::Pending !== $order->status) {
            throw new InvalidArgumentException("Only pending orders can be deleted");
        }

        $order->delete();
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Create order
            $order = Order::create([
                'total_amount' => $data['total'],
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'order_type' => $data['order_type']
            ]);

            // Create order items
            $orderItems = collect($data['items'])->map(fn ($item) => [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'size' => $item['size'] ?? null,
            ])->all();

            $order->items()->createMany($orderItems);

            return $order;
        });
    }
}
