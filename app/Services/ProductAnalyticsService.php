<?php


declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductAnalyticsService
{
    public function getProductStatistics(Product $product, Carbon $startDate, Carbon $endDate): array
    {
        $orderItems = $product->orderItems()
            ->select([
                DB::raw('COUNT(DISTINCT order_id) as total_orders'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('COUNT(CASE WHEN created_at >= ? THEN 1 END) as recent_orders', [now()->subDays(30)]),
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->first();

        $totalRevenue = $product->orderItems()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * price'));

        $totalCost = $product->orderItems()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * cost'));

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
        return Product::query()
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

    public function updatePopularityScore(Product $product): void
    {
        $thirtyDaysAgo = now()->subDays(30);

        $score = $product->orderItems()
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('quantity');

        $product->popularity_score = $score;
        $product->save();
    }

    public function getRelatedProducts(Product $product, int $limit = 5): Collection
    {
        return Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take($limit)
            ->get();
    }
}
