<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function getPopularCombinations(int $limit = 5): Collection
    {
        return Order::with('items')
            ->select('orders.*')
            ->selectRaw('COUNT(*) as order_count')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('orders.id')
            ->orderByDesc('order_count')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                return [
                    'items' => $order->items->pluck('name'),
                    'count' => $order->order_count,
                ];
            });
    }

    public function getRecommendedProducts(array $currentItems, int $limit = 3): Collection
    {
        $popularProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(10)
            ->get();

        return $popularProducts->filter(fn ($product) => ! in_array($product->name, $currentItems))->take($limit);
    }
}
