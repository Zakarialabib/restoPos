<div>
    <div class="p-6 bg-gray-100 rounded-lg shadow-lg">
        <!-- Date Range Selector -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <select wire:model.live="dateRange" class="rounded-md border-gray-300 focus:ring focus:ring-blue-300">
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="this_year">This Year</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="custom">Custom Range</option>
                    </select>

                    @if ($dateRange === 'custom')
                        <div class="flex items-center space-x-2">
                            <x-input type="date" wire:model.live="customStartDate" class="border-gray-300" />
                            <span>to</span>
                            <x-input type="date" wire:model.live="customEndDate" class="border-gray-300" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <x-stats-card title="Total Revenue" :value="number_format($this->totalRevenue, 2) . ' DH'" icon="paid" color="blue" />

            <x-stats-card title="Total Orders" :value="$this->totalOrders" icon="shopping_cart" color="green" />

            <x-stats-card title="Average Order Value" :value="number_format($this->averageOrderValue, 2) . ' DH'" icon="bar_chart" color="purple" />
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-white border border-gray-200 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Order ID</th>
                                <th class="px-4 py-2 text-left">Customer</th>
                                <th class="px-4 py-2 text-left">Amount</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td class="px-4 py-2">#{{ $order->id }}</td>
                                    <td class="px-4 py-2">{{ $order->customer_name ?? 'Guest' }}</td>
                                    <td class="px-4 py-2">{{ number_format($order->total_amount, 2) }} DH</td>
                                    <td class="px-4 py-2">{{ $order->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center">No recent orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white border border-gray-200 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Low Stock Alerts</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-left">Category</th>
                                <th class="px-4 py-2 text-left">Stock</th>
                                <th class="px-4 py-2 text-left">Reorder Point</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td class="px-4 py-2">{{ $product->name }}</td>
                                    <td class="px-4 py-2">{{ $product->category->name }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->stock_quantity === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $product->reorder_point }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center">No low stock products</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Category Performance -->
            <div class="bg-white border border-gray-200 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Category Performance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Category</th>
                                <th class="px-4 py-2 text-left">Total Orders</th>
                                <th class="px-4 py-2 text-left">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($this->categoryPerformance as $category)
                                <tr>
                                    <td class="px-4 py-2">{{ $category['name'] }}</td>
                                    <td class="px-4 py-2">{{ $category['total_orders'] }}</td>
                                    <td class="px-4 py-2">{{ number_format($category['total_revenue'], 2) }} DH</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center">No category data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

              <!-- Top Products -->
              <div class="border-t pt-4">
                <h4 class="text-md font-medium mb-4">{{ __('Top Products') }}</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                    {{ __('Product') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                    {{ __('Orders') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                    {{ __('Quantity') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                    {{ __('Revenue') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                    {{ __('Sales Trends') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($this->productAnalytics['top_products'] as $product)
                                <tr>
                                    <td class="px-4 py-2">{{ $product['name'] }}</td>
                                    <td class="px-4 py-2">{{ $product['total_orders'] }}</td>
                                    <td class="px-4 py-2">{{ number_format($product['total_quantity'], 2) }}</td>
                                    <td class="px-4 py-2">{{ number_format($product['total_revenue'], 2) }} DH</td>
                                    <td class="px-4 py-2">
                                        @if(isset($this->productAnalytics['sales_trends']['daily_sales']))
                                            {{ number_format($this->productAnalytics['sales_trends']['average_daily_sales'], 2) }}
                                            <span class="text-xs text-gray-500">{{ __('avg/day') }}</span>
                                        @else
                                            {{ __('No data') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div x-data="{ expanded: false }" class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">{{ __('Stock Alerts') }}</h3>
                <div class="flex flex-row items-center gap-x-2">
                    <select wire:model.live="category_filter" id="category_filter"
                        class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}
                                {{-- ({{ $category->products_count }}) --}}
                            </option>
                        @endforeach
                    </select>
                    <button @click="expanded = !expanded" class="text-gray-500 hover:text-gray-700">
                        <span class="material-icons">
                            <span x-show="!expanded" class="material-icons">expand_more</span>
                            <span x-show="expanded" class="material-icons">expand_less</span>
                        </span>
                    </button>
                </div>
            </div>
            @if ($this->stockAlerts->isNotEmpty())
                <div x-show="expanded" x-collapse class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($this->stockAlerts as $alert)
                        <div
                            class="border p-3 rounded-lg {{ $alert['status'] === 'out_of_stock' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }}">
                            <p class="font-medium">{{ $alert['name'] }}</p>
                            <p class="text-sm">
                                {{ __('Current Stock') }}: {{ $alert['current_stock'] }}
                                ({{ __('Reorder Point') }}: {{ $alert['reorder_point'] }})
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Revenue Trends</h3>
                <div wire:ignore>
                    <canvas id="revenueChart" x-data="{
                        chart: null,
                        init() {
                            this.chart = new Chart(document.getElementById('revenueChart'), {
                                type: 'line',
                                data: {
                                    labels: @json($chartData['revenue']->pluck('date')),
                                    datasets: [{
                                        label: 'Revenue',
                                        data: @json($chartData['revenue']->pluck('revenue')),
                                        borderColor: 'rgb(59, 130, 246)',
                                        tension: 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        }
                                    }
                                }
                            });
                    
                            Livewire.on('updateChartData', (data) => {
                                this.chart.data.labels = data.revenue.map(d => d.date);
                                this.chart.data.datasets[0].data = data.revenue.map(d => d.revenue);
                                this.chart.update();
                            });
                        }
                    }"></canvas>
                </div>
            </div>

            <!-- Predictive Analytics Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Predictive Analytics</h3>
                <div wire:ignore>
                    <canvas id="predictiveChart" x-data="{
                        chart: null,
                        init() {
                            this.chart = new Chart(document.getElementById('predictiveChart'), {
                                type: 'line',
                                data: {
                                    labels: @json($chartData['predictions']->pluck('date')),
                                    datasets: [{
                                        label: 'Predicted Revenue',
                                        data: @json($chartData['predictions']->pluck('revenue')),
                                        borderColor: 'rgb(139, 92, 246)',
                                        tension: 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        }
                                    }
                                }
                            });
                        }
                    }"></canvas>
                </div>
            </div>
        </div>

        <!-- Real-time Sales Monitoring -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Real-time Sales Monitor</h3>
            </div>

            <div wire:poll.{{ $refreshInterval }}s>
                @foreach ($recentOrders as $order)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-gray-400">receipt</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->customer_name ?? 'Guest' }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-900">{{ number_format($order->total_amount, 2) }} DH</div>
                    </div>
                @endforeach
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('downloadReport', (data) => {
                        const csvContent = 'data:text/csv;charset=utf-8,' +
                            data.data.map(row => Object.values(row).join(',')).join('\n');

                        const encodedUri = encodeURI(csvContent);
                        const link = document.createElement('a');
                        link.setAttribute('href', encodedUri);
                        link.setAttribute('download', `report_${data.type}_${data.startDate}_${data.endDate}.csv`);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                });
            </script>
        @endpush

    </div>
</div>
