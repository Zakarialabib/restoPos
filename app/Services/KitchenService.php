<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\KitchenOrderPriority;
use App\Enums\KitchenOrderStatus;
use App\Models\KitchenOrder;
use App\Models\KitchenOrderItem;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KitchenService
{
    public function createKitchenOrder(Order $order): KitchenOrder
    {
        try {
            return DB::transaction(function () use ($order) {
                $kitchenOrder = KitchenOrder::create([
                    'order_id' => $order->id,
                    'status' => KitchenOrderStatus::Pending,
                    'priority' => $this->determinePriority($order),
                    'estimated_preparation_time' => $this->calculateEstimatedTime($order),
                    'notes' => $order->notes,
                ]);

                $items = $order->items->map(fn ($orderItem) => [
                    'order_item_id' => $orderItem->id,
                    'status' => KitchenOrderStatus::Pending->value,
                    'notes' => $orderItem->notes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                $kitchenOrder->items()->insert($items);

                Log::info('Kitchen order created', [
                    'order_id' => $order->id,
                    'kitchen_order_id' => $kitchenOrder->id,
                    'items_count' => count($items)
                ]);

                return $kitchenOrder;
            });
        } catch (Exception $e) {
            Log::error('Failed to create kitchen order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to create kitchen order: ' . $e->getMessage());
        }
    }

    public function assignKitchenOrder(KitchenOrder $kitchenOrder, int $userId): void
    {
        try {
            DB::transaction(function () use ($kitchenOrder, $userId): void {
                $startTime = now();
                $oldStatus = $kitchenOrder->status;

                $kitchenOrder->update([
                    'assigned_to' => $userId,
                    'status' => KitchenOrderStatus::InProgress,
                    'started_at' => $startTime,
                ]);

                $kitchenOrder->items()
                    ->where('status', KitchenOrderStatus::Pending->value)
                    ->update([
                        'status' => KitchenOrderStatus::InProgress->value,
                        'started_at' => $startTime,
                    ]);

                Log::info('Kitchen order assigned', [
                    'kitchen_order_id' => $kitchenOrder->id,
                    'assigned_to' => $userId
                ]);

                // Dispatch event for real-time updates
                event(new \App\Events\KitchenOrderStatusChanged($kitchenOrder, $oldStatus, KitchenOrderStatus::InProgress));
            });
        } catch (Exception $e) {
            Log::error('Failed to assign kitchen order', [
                'kitchen_order_id' => $kitchenOrder->id,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to assign kitchen order: ' . $e->getMessage());
        }
    }

    public function updateItemStatus(KitchenOrderItem $item, string $status): void
    {
        $oldStatus = $item->status;
        $kitchenOrder = $item->kitchenOrder;

        DB::transaction(function () use ($item, $status, $kitchenOrder): void {
            $item->update([
                'status' => $status,
                'completed_at' => $status === KitchenOrderStatus::Completed->value ? now() : null,
            ]);

            $this->checkAndUpdateKitchenOrderStatus($kitchenOrder);

            if ($status === KitchenOrderStatus::Completed->value) {
                $this->updateIngredientStock($item);
            }
        });

        // If the kitchen order status hasn't changed but the item status has,
        // we still want to broadcast an event for real-time updates
        if ($kitchenOrder->status === $kitchenOrder->fresh()->status) {
            event(new \App\Events\KitchenOrderStatusChanged($kitchenOrder, $kitchenOrder->status, $kitchenOrder->status));
        }
    }

    public function getActiveOrders(): array
    {
        return KitchenOrder::with(['order', 'items.orderItem.product', 'assignedTo'])
            ->whereIn('status', [KitchenOrderStatus::Pending->value, KitchenOrderStatus::InProgress->value])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($kitchenOrder) => [
                'id' => $kitchenOrder->id,
                'reference' => $kitchenOrder->order->reference,
                'status' => $kitchenOrder->status,
                'priority' => $kitchenOrder->priority,
                'assigned_to' => $kitchenOrder->assignedTo?->name,
                'items' => $kitchenOrder->items->map(fn ($item) => [
                    'id' => $item->id,
                    'product' => $item->orderItem->product->name,
                    'quantity' => $item->orderItem->quantity,
                    'status' => $item->status,
                    'notes' => $item->notes,
                    'started_at' => $item->started_at?->diffForHumans(),
                    'preparation_time' => $item->started_at
                        ? $item->started_at->diffInMinutes(now())
                        : 0,
                ]),
                'estimated_time' => $kitchenOrder->estimated_preparation_time,
                'started_at' => $kitchenOrder->started_at?->diffForHumans(),
                'elapsed_time' => $kitchenOrder->started_at
                    ? $kitchenOrder->started_at->diffInMinutes(now())
                    : 0,
                'is_delayed' => $kitchenOrder->started_at &&
                    $kitchenOrder->started_at->addMinutes($kitchenOrder->estimated_preparation_time) < now(),
            ])
            ->toArray();
    }

    public function getOrdersByStatus(KitchenOrderStatus $status): Collection
    {
        return KitchenOrder::query()
            ->with(['items', 'items.product'])
            ->where('status', $status)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getDelayedOrders(): Collection
    {
        return KitchenOrder::query()
            ->with(['items', 'items.product'])
            ->where('status', '!=', KitchenOrderStatus::Completed)
            ->where('created_at', '<=', now()->subMinutes(30))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getOrderById(int $orderId): ?KitchenOrder
    {
        return KitchenOrder::with(['items', 'items.product'])
            ->find($orderId);
    }

    public function updateOrderStatus(KitchenOrder $order, KitchenOrderStatus $newStatus): bool
    {
        return $order->update(['status' => $newStatus]);
    }

    private function determinePriority(Order $order): KitchenOrderPriority
    {
        // VIP customers get high priority
        if ($order->customer && $order->customer->is_vip) {
            return KitchenOrderPriority::High;
        }

        // Delivery orders typically need faster processing
        if ('delivery' === $order->type) {
            return KitchenOrderPriority::High;
        }

        // Orders with many items get higher priority
        if ($order->items->sum('quantity') > 5) {
            return KitchenOrderPriority::High;
        }

        // Orders with items that take long to prepare
        $hasComplexItems = $order->items->contains(fn ($item) => $item->product->preparation_time > 20);

        if ($hasComplexItems) {
            return KitchenOrderPriority::High;
        }

        return KitchenOrderPriority::Medium;
    }

    private function calculateEstimatedTime(Order $order): int
    {
        $baseTime = 0;
        $maxParallelItems = 3; // Assuming kitchen can handle 3 items simultaneously

        // Group items by product to handle multiple quantities
        $itemGroups = $order->items->groupBy('product_id');

        foreach ($itemGroups as $items) {
            $product = $items->first()->product;
            $quantity = $items->sum('quantity');

            // Calculate time needed for this product group
            $productTime = $product->preparation_time * ceil($quantity / $maxParallelItems);

            // Update base time (items can be prepared in parallel)
            $baseTime = max($baseTime, $productTime);
        }

        // Add buffer time based on kitchen load
        $activeOrders = KitchenOrder::whereIn('status', [KitchenOrderStatus::Pending->value, KitchenOrderStatus::InProgress->value])->count();
        $bufferTime = min(15, $activeOrders * 5); // Max 15 minutes buffer

        return $baseTime + $bufferTime;
    }

    private function checkAndUpdateKitchenOrderStatus(KitchenOrder $kitchenOrder): void
    {
        $oldStatus = $kitchenOrder->status;
        $allItemsCompleted = $kitchenOrder->items()
            ->where('status', '!=', KitchenOrderStatus::Completed->value)
            ->doesntExist();

        if ($allItemsCompleted) {
            $kitchenOrder->update([
                'status' => KitchenOrderStatus::Completed->value,
                'completed_at' => now(),
            ]);

            // Dispatch event for real-time updates
            event(new \App\Events\KitchenOrderStatusChanged($kitchenOrder, $oldStatus, KitchenOrderStatus::Completed));
        }
    }

    private function updateIngredientStock(KitchenOrderItem $item): void
    {
        $product = $item->orderItem->product;
        $quantity = $item->orderItem->quantity;

        // Deduct ingredients from stock based on recipe
        foreach ($product->recipe->ingredients as $ingredient) {
            $ingredient->decrement(
                'stock_quantity',
                $ingredient->pivot->quantity * $quantity
            );

            // Log the stock movement
            $ingredient->stockLogs()->create([
                'type' => 'consumption',
                'quantity' => $ingredient->pivot->quantity * $quantity,
                'reference_type' => KitchenOrderItem::class,
                'reference_id' => $item->id,
                'notes' => "Used in order #{$item->kitchenOrder->order->reference}",
            ]);
        }
    }
}
