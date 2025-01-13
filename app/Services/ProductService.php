<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProduct(array $data, array $sizePrices = [], array $ingredients = []): Product
    {
        return DB::transaction(function () use ($data, $sizePrices, $ingredients) {
            $product = Product::create($data);

            if (!empty($sizePrices)) {
                foreach ($sizePrices as $sizePrice) {
                    $product->prices()->create([
                        'price' => $sizePrice['price'],
                        'cost' => $sizePrice['cost'],
                        'is_current' => true,
                        'entry_date' => now(),
                        'metadata' => [
                            'size' => $sizePrice['size'],
                            'unit' => $sizePrice['unit'],
                        ],
                    ]);
                }
            }

            if (!empty($ingredients)) {
                foreach ($ingredients as $ingredient) {
                    $product->ingredients()->attach($ingredient['id'], [
                        'quantity' => $ingredient['quantity'],
                        'unit' => $ingredient['unit'],
                    ]);
                }
            }

            return $product;
        });
    }

    public function updateProduct(Product $product, array $data, array $sizePrices = [], array $ingredients = []): void
    {
        DB::transaction(function () use ($product, $data, $sizePrices, $ingredients) {
            $product->update($data);

            if (!empty($sizePrices)) {
                $product->prices()->delete();
                foreach ($sizePrices as $sizePrice) {
                    $product->prices()->create([
                        'price' => $sizePrice['price'],
                        'cost' => $sizePrice['cost'],
                        'is_current' => true,
                        'entry_date' => now(),
                        'metadata' => [
                            'size' => $sizePrice['size'],
                            'unit' => $sizePrice['unit'],
                        ],
                    ]);
                }
            }

            if (!empty($ingredients)) {
                $product->ingredients()->detach();
                foreach ($ingredients as $ingredient) {
                    $product->ingredients()->attach($ingredient['id'], [
                        'quantity' => $ingredient['quantity'],
                        'unit' => $ingredient['unit'],
                    ]);
                }
            }
        });
    }

    public function deleteProduct(Product $product): void
    {
        if ($product->orderItems()->exists()) {
            throw new \InvalidArgumentException("Cannot delete product with existing orders");
        }

        DB::transaction(function () use ($product) {
            $product->ingredients()->detach();
            $product->prices()->delete();
            $product->stockLogs()->delete();
            $product->delete();
        });
    }

    public function toggleStatus(Product $product): void
    {
        $product->status = !$product->status;
        $product->save();
    }

    public function toggleFeatured(Product $product): void
    {
        $product->is_featured = !$product->is_featured;
        $product->save();
    }

    public function getProductAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', true)->count(),
            'low_stock_count' => Product::lowStock()->count(),
            'featured_count' => Product::featured()->count(),
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'stock_alerts' => $this->getStockAlerts(),
            'sales_trends' => $this->getSalesTrends($startDate, $endDate),
        ];
    }

    protected function getTopProducts(Carbon $startDate, Carbon $endDate): Collection
    {
        return Product::withSum(['orderItems' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }], 'quantity')
            ->withSum(['orderItems' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], DB::raw('quantity * price'))
            ->withCount(['orderItems' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('order_items_sum_quantity')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'total_orders' => $product->order_items_count,
                    'total_quantity' => $product->order_items_sum_quantity,
                    'total_revenue' => $product->order_items_sum_quantity_price,
                ];
            });
    }

    protected function getStockAlerts(): Collection
    {
        return Product::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->where('stock_quantity', '<=', DB::raw('reorder_point'))
                    ->orWhereHas('stockLogs', function ($query) {
                        $query->whereDate('created_at', '>=', now()->subDays(30))
                            ->where('adjustment', '<', 0); // Use adjustment column instead of type
                    });
            })
            ->with(['stockLogs' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock_quantity' => $product->stock_quantity,
                    'reorder_point' => $product->reorder_point,
                    'status' => $product->stockStatus,
                    'last_stock_movement' => $product->stockLogs->first(),
                    'stock_history' => $product->stockLogs,
                ];
            });
    }

    protected function getSalesTrends(Carbon $startDate, Carbon $endDate): array
    {
        $dailySales = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'daily_sales' => $dailySales,
            'total_revenue' => $dailySales->sum('total_revenue'),
            'average_daily_sales' => $dailySales->avg('total_quantity'),
        ];
    }


}
