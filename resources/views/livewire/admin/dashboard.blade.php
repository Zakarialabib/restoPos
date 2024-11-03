<?php

use function Livewire\Volt\{state, layout, title, computed, usesPagination};
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

title('Dashboard');
layout('layouts.app');

usesPagination();

state([
    'totalProducts' => Product::count(),
    'recentOrders' => Order::latest()->take(5)->get(),
    'startDate' => fn() => Carbon::now()->startOfMonth(),
    'endDate' => fn() => Carbon::now(),
    'dateRange' => 'this_month',
    'search' => '',
    'totalRevenue' => 0,
    'totalOrders' => 0,
    'newCustomers' => 0,
    'averageOrderValue' => 0,
    'topSellingProducts' => [],
]);

$setDateRange = function ($range) {
    $this->dateRange = $range;
    switch ($range) {
        case 'today':
            $this->startDate = Carbon::today();
            $this->endDate = Carbon::today();
            break;
        case 'this_week':
            $this->startDate = Carbon::now()->startOfWeek();
            $this->endDate = Carbon::now()->endOfWeek();
            break;
        case 'this_month':
            $this->startDate = Carbon::now()->startOfMonth();
            $this->endDate = Carbon::now()->endOfMonth();
            break;
        case 'this_year':
            $this->startDate = Carbon::now()->startOfYear();
            $this->endDate = Carbon::now()->endOfYear();
            break;
    }
};

$totalRevenue = computed(function () {
    return Order::whereBetween('created_at', [$this->startDate, $this->endDate])->sum('total');
});

$totalOrders = computed(function () {
    return Order::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
});

$newCustomers = computed(function () {
    return User::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
});

$averageOrderValue = computed(function () {
    return $this->totalOrders > 0 ? $this->totalRevenue / $this->totalOrders : 0;
});

$topSellingProducts = computed(function () {
    return Order::whereBetween('created_at', [$this->startDate, $this->endDate])
        ->with('orderItems.product')
        ->get()
        ->flatMap->orderItems->groupBy('product.name')
        ->map(function ($group) {
            return [
                'name' => $group->first()->product->name,
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
            ];
        })
        ->sortByDesc('revenue')
        ->take(5)
        ->values();
});

?>

<div>
    <div class="w-full py-12">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        <!-- Date Range Selector -->
        <div class="mb-8 flex items-center space-x-4">
            <label for="dateRange" class="text-sm font-medium text-gray-700">Date Range:</label>
            <select id="dateRange" wire:model="dateRange" wire:change="setDateRange($event.target.value)"
                class="mt-1 block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="today">Today</option>
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
                <option value="this_year">This Year</option>
            </select>
            <span class="text-sm text-gray-500">
                {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
            </span>
        </div>


        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-2">Total Revenue</h2>
                <p class="text-3xl font-bold">{{ number_format($this->totalRevenue, 2) }} DH</p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-2">Total Orders</h2>
                <p class="text-3xl font-bold">{{ $this->totalOrders }}</p>
            </div>
            <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-2">New Customers</h2>
                <p class="text-3xl font-bold">{{ $this->newCustomers }}</p>
            </div>
            <div class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-2">Average Order Value</h2>
                <p class="text-3xl font-bold">{{ number_format($this->averageOrderValue, 2) }} DH</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Total Products Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-yellow-400">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-400 text-white rounded-full">
                        <span class="material-icons text-yellow-500">inventory</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-800">Total Products</h2>
                        <p class="text-3xl font-bold text-gray-700">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order?->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order?->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order?->total_amount }}DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order?->status->color() }}">
                                    {{ $order?->status->label() }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products Table -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Low Stock Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock</th>
                        </tr>
                    </thead>
                    {{--  <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($lowStockProductsList as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $product->stock }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">No products found</td>
                        </tr>
                        @endforelse
                    </tbody> --}}
                </table>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Top Selling Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->topSellingProducts as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product['quantity'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product['revenue'], 2) }} DH
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>