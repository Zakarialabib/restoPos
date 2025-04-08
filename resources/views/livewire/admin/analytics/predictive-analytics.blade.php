<div>
    <div class="space-y-6">
        <!-- Header with Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ __('Predictive Analytics') }}</h3>
                <p class="text-sm text-gray-600">{{ __('AI-powered insights and forecasting') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <select wire:model.live="timeRange" class="rounded-lg border-gray-300 shadow-sm">
                    <option value="day">{{ __('Daily') }}</option>
                    <option value="week">{{ __('Weekly') }}</option>
                    <option value="month">{{ __('Monthly') }}</option>
                </select>
                <x-button wire:click="refreshPredictions" color="primary">
                    <span class="material-icons">refresh</span>
                    {{ __('Refresh') }}
                </x-button>
            </div>
        </div>

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-600">{{ __('Predicted Revenue') }}</h4>
                    <span class="text-xs px-2 py-1 rounded-full {{ $predictions['sales_forecast']['trend'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $predictions['sales_forecast']['trend'] > 0 ? '+' : '' }}{{ number_format($predictions['sales_forecast']['trend'], 1) }}%
                    </span>
                </div>
                <p class="text-2xl font-bold mt-2">{{ number_format($predictions['sales_forecast']['total'], 2) }} DH</p>
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('vs Last') }} {{ ucfirst($timeRange) }}: {{ number_format($predictions['sales_forecast']['previous'], 2) }} DH
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-600">{{ __('Predicted Orders') }}</h4>
                    <span class="text-xs px-2 py-1 rounded-full {{ $predictions['demand_forecast']['trend'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $predictions['demand_forecast']['trend'] > 0 ? '+' : '' }}{{ number_format($predictions['demand_forecast']['trend'], 1) }}%
                    </span>
                </div>
                <p class="text-2xl font-bold mt-2">{{ number_format($predictions['demand_forecast']['total']) }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('Confidence Score') }}: {{ number_format($predictions['demand_forecast']['confidence_score'], 1) }}%
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-600">{{ __('Stock Optimization') }}</h4>
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                        {{ number_format($predictions['stock_recommendations']['cost_savings'], 0) }} DH
                    </span>
                </div>
                <p class="text-2xl font-bold mt-2">{{ $predictions['stock_recommendations']['items_to_reorder'] }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('Items Need Attention') }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-600">{{ __('Peak Hours') }}</h4>
                    <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-800">
                        {{ __('Today') }}
                    </span>
                </div>
                <p class="text-2xl font-bold mt-2">{{ $predictions['peak_hours']['peak_time'] }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('Expected Orders') }}: {{ number_format($predictions['peak_hours']['expected_orders']) }}
                </div>
            </div>
        </div>

        <!-- Sales Forecast Chart -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-medium">{{ __('Sales Forecast') }}</h4>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        {{ __('Predicted') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-gray-300"></span>
                        {{ __('Actual') }}
                    </span>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas x-data x-init="new Chart($el, {
                    type: 'line',
                    data: {
                        labels: @js($predictions['sales_forecast']['dates']),
                        datasets: [
                            {
                                label: '{{ __('Predicted Sales') }}',
                                data: @js($predictions['sales_forecast']['amounts']),
                                borderColor: '#3B82F6',
                                backgroundColor: '#93C5FD',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: '{{ __('Actual Sales') }}',
                                data: @js($predictions['sales_forecast']['actual']),
                                borderColor: '#9CA3AF',
                                borderDash: [5, 5],
                                tension: 0.4,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' DH';
                                    }
                                }
                            }
                        }
                    }
                })"></canvas>
            </div>
        </div>

        <!-- Seasonal Analysis -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Daily Patterns -->
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-lg font-medium mb-4">{{ __('Daily Traffic Pattern') }}</h4>
                <div class="relative" style="height: 250px;">
                    <canvas x-data x-init="new Chart($el, {
                        type: 'bar',
                        data: {
                            labels: @js($predictions['seasonal_trends']['daily_patterns']['time_slots']),
                            datasets: [{
                                label: '{{ __('Order Volume') }}',
                                data: @js($predictions['seasonal_trends']['daily_patterns']['order_counts']),
                                backgroundColor: '#818CF8',
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __('Orders') }}'
                                    }
                                }
                            }
                        }
                    })"></canvas>
                </div>
            </div>

            <!-- Weekly Patterns -->
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-lg font-medium mb-4">{{ __('Weekly Performance') }}</h4>
                <div class="relative" style="height: 250px;">
                    <canvas x-data x-init="new Chart($el, {
                        type: 'line',
                        data: {
                            labels: ['{{ __('Mon') }}', '{{ __('Tue') }}', '{{ __('Wed') }}', '{{ __('Thu') }}', '{{ __('Fri') }}', '{{ __('Sat') }}', '{{ __('Sun') }}'],
                            datasets: [{
                                label: '{{ __('Orders') }}',
                                data: @js($predictions['seasonal_trends']['weekly_patterns']['order_counts']),
                                borderColor: '#8B5CF6',
                                backgroundColor: '#DDD6FE',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __('Orders') }}'
                                    }
                                }
                            }
                        }
                    })"></canvas>
                </div>
            </div>
        </div>

        <!-- Inventory Recommendations -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-medium">{{ __('Inventory Recommendations') }}</h4>
                <x-button wire:click="applyRecommendations" color="success" size="sm">
                    {{ __('Apply All') }}
                </x-button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Product') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Current Stock') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Recommended') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Action') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Confidence') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($predictions['inventory_forecast'] as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $product['name'] }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product['current_stock'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product['recommended_stock'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $product['action'] === 'restock' ? 'bg-red-100 text-red-800' : 
                                           ($product['action'] === 'reduce' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ __(ucfirst($product['action'])) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900">{{ number_format($product['confidence'], 1) }}%</div>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $product['confidence'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-button wire:click="applyRecommendation({{ $product['id'] }})" color="primary" size="xs">
                                        {{ __('Apply') }}
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Special Events Impact -->
        <div class="bg-white rounded-lg shadow p-4">
            <h4 class="text-lg font-medium mb-4">{{ __('Special Events Impact') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($predictions['seasonal_trends']['special_events'] as $event)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <h5 class="font-medium text-gray-900">{{ $event['name'] }}</h5>
                            <span class="text-xs px-2 py-1 rounded-full {{ $event['impact'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event['impact'] > 0 ? '+' : '' }}{{ number_format($event['impact'], 1) }}%
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">{{ $event['date'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $event['description'] }}</p>
                        <div class="mt-3">
                            <div class="text-xs text-gray-500">{{ __('Recommended Actions') }}:</div>
                            <ul class="text-sm text-gray-600 list-disc list-inside mt-1">
                                @foreach($event['recommendations'] as $recommendation)
                                    <li>{{ $recommendation }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
