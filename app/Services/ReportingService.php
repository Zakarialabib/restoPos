<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    public function getRevenueData(Carbon $startDate, Carbon $endDate): Collection
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getTopProducts(Carbon $startDate, Carbon $endDate, int $limit = 5): Collection
    {
        return Product::withSum(['orderItems' => function ($query) use ($startDate, $endDate): void {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }], 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take($limit)
            ->get();
    }

    public function getLowStockProducts(int $limit = 5): Collection
    {
        return Product::where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->where('status', true)
            ->orderBy('stock_quantity')
            ->take($limit)
            ->get();
    }

    public function getCategoryPerformance(Carbon $startDate, Carbon $endDate, int $limit = 5): Collection
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->take($limit)
            ->get();
    }

    public function getRecipePreparationMetrics(Carbon $startDate, Carbon $endDate): Collection
    {
        return DB::table('recipes')
            ->select(
                'type',
                DB::raw('AVG(preparation_time) as avg_prep_time'),
                DB::raw('COUNT(*) as recipe_count'),
                DB::raw('AVG(CASE WHEN preparation_steps IS NOT NULL 
                THEN JSON_LENGTH(preparation_steps) ELSE 0 END) as avg_steps')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('type')
            ->get();
    }
}
