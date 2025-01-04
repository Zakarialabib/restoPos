<div>
    <div class="w-full">

        @error('order')
            <x-alert type="error" :dismissal="false" :showIcon="true">
                {{ $message }}
            </x-alert>
        @enderror
        <!-- Header with Analytics -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Order Management') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('Manage and track orders') }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-button wire:click="$toggle('showAnalytics')" color="success">
                        <span class="material-icons">bar_chart</span>
                        {{ $showAnalytics ? __('Hide Analytics') : __('Show Analytics') }}
                    </x-button>
                    @if (count($selectedOrders) > 0)
                        <x-button wire:click="$toggle('showBulkActions')" color="warning">
                            <span class="material-icons">collections</span>
                            {{ __('Bulk Actions') }}
                        </x-button>
                    @endif
                </div>
            </div>

            @if ($showAnalytics)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Total Revenue') }}</h3>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ __('Today') }}
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($this->orderAnalytics['total_revenue'], 2) }} DH</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="material-icons">trending_up</span>
                            <span>{{ __('12% increase') }}</span>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Total Profit') }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ __('This Month') }}
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($this->orderAnalytics['total_profit'], 2) }} DH</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="material-icons">trending_up</span>
                            <span>{{ __('8% increase') }}</span>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Average Order Value') }}</h3>
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ __('Last 30 Days') }}
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($this->orderAnalytics['average_order_value'], 2) }} DH</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="material-icons">trending_down</span>
                            <span>{{ __('3% decrease') }}</span>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ __('This Week') }}
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $this->orderAnalytics['order_count'] }}</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="material-icons">trending_up</span>
                            <span>{{ __('15% increase') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filters Panel -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons">search</span>
                        </div>
                        <x-input type="text" wire:model.live="search" class="pl-10 "
                            placeholder="{{ __('Search orders...') }}" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select wire:model.live="selectedStatus"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach (App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date Range') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons">calendar_month</span>
                        </div>
                        <x-input type="text" wire:model="dateRange" x-data x-init="flatpickr($el, { mode: 'range' })" class="pl-10 "
                            placeholder="{{ __('Select date range') }}" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Time Filter') }}</label>
                    <select wire:model.live="timeFilter"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="all">{{ __('All Time') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="yesterday">{{ __('Yesterday') }}</option>
                        <option value="this_week">{{ __('This Week') }}</option>
                        <option value="this_month">{{ __('This Month') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Payment Status') }}</label>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" wire:model.live="onlyUnpaid"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Show only unpaid orders') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Panel -->
        @if ($showBulkActions && count($selectedOrders) > 0)
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">
                        {{ __(':count orders selected', ['count' => count($selectedOrders)]) }}
                    </span>
                    <div class="flex space-x-3">
                        <x-button wire:click="bulkUpdateStatus('completed')" color="success">
                            <span class="material-icons">check</span>
                            {{ __('Mark Completed') }}
                        </x-button>
                        <x-button wire:click="bulkMarkAsPaid" color="warning">
                            <span class="material-icons">paid</span>
                            {{ __('Mark Paid') }}
                        </x-button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <x-table>
                <x-slot name="header">
                    <tr>
                        <x-table.th class="w-8">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </x-table.th>
                        <x-table.th wire:click="sortBy('id')" class="cursor-pointer">
                            <div class="flex items-center">
                                {{ __('Order ID') }}
                                @if ($sortField === 'id')
                                    <span
                                        class="material-icons">{{ $sortDirection === 'asc' ? 'chevron_up' : 'chevron_down' }}</span>
                                @endif
                            </div>
                        </x-table.th>
                        <x-table.th>{{ __('Customer') }}</x-table.th>
                        <x-table.th wire:click="sortBy('total_amount')" class="cursor-pointer">
                            <div class="flex items-center">
                                {{ __('Total') }}
                                @if ($sortField === 'total_amount')
                                    <span
                                        class="material-icons">{{ $sortDirection === 'asc' ? 'chevron_up' : 'chevron_down' }}</span>
                                @endif
                            </div>
                        </x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th wire:click="sortBy('created_at')" class="cursor-pointer">
                            <div class="flex items-center">
                                {{ __('Date') }}
                                @if ($sortField === 'created_at')
                                    <span
                                        class="material-icons">{{ $sortDirection === 'asc' ? 'chevron_up' : 'chevron_down' }}</span>
                                @endif
                            </div>
                        </x-table.th>
                        <x-table.th>{{ __('Actions') }}</x-table.th>
                    </tr>
                </x-slot>

                <tbody class="divide-y divide-gray-200">
                    @forelse($this->orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <x-table.td>
                                <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </x-table.td>
                            <x-table.td>
                                <span class="font-medium">#{{ $order->id }}</span>
                            </x-table.td>
                            <x-table.td>
                                @if ($order->customer_name)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span
                                                class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                                                <span class="text-lg font-medium leading-none text-white">
                                                    {{ substr($order->customer_name, 0, 1) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">{{ __('Guest') }}</div>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($order->total_amount, 2) }} DH
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ __('Profit') }}: {{ number_format($order->getProfit(), 2) }} DH
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)"
                                    @class([
                                        'text-xs font-semibold rounded-full px-3 py-1 border-0 focus:ring-2 focus:ring-offset-2',
                                        'bg-yellow-100 text-yellow-800' =>
                                            $order->status === \App\Enums\OrderStatus::Pending,
                                        'bg-blue-100 text-blue-800' =>
                                            $order->status === \App\Enums\OrderStatus::Processing,
                                        'bg-green-100 text-green-800' =>
                                            $order->status === \App\Enums\OrderStatus::Completed,
                                        'bg-red-100 text-red-800' =>
                                            $order->status === \App\Enums\OrderStatus::Cancelled,
                                    ])>
                                    @foreach (App\Enums\OrderStatus::cases() as $status)
                                        <option value="{{ $status->value }}" @selected($order->status === $status)>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center space-x-2">
                                    @if (!$order->is_paid)
                                        <x-button size="sm" color="success"
                                            wire:click="markAsPaid({{ $order->id }})">
                                            <span class="material-icons">paid</span>
                                        </x-button>
                                    @endif
                                    <x-button size="sm" color="info"
                                        wire:click="viewOrderDetails({{ $order->id }})">
                                        <span class="material-icons">visibility</span>
                                    </x-button>
                                    <x-button size="sm" color="danger"
                                        wire:click="deleteOrder({{ $order->id }})">
                                        <span class="material-icons">delete</span>
                                    </x-button>
                                </div>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7">
                                <div class="flex flex-col items-center justify-center py-12">
                                    <span class="material-icons">emoji_sad</span>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No orders found') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('No orders match your current filters.') }}</p>
                                </div>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $this->orders->links() }}
        </div>

        <!-- Order Details Modal -->
        @if ($showOrderDetails && $selectedOrder)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"></div>
            <div class="fixed inset-0 overflow-y-auto z-50">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                        <div class="absolute right-0 top-0 pr-4 pt-4">
                            <button wire:click="$set('showOrderDetails', false)"
                                class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                                <span class="material-icons">close</span>
                            </button>
                        </div>

                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                    {{ __('Order Details') }} #{{ $selectedOrder->id }}
                                </h3>

                                <div class="mt-4 border-t border-gray-200 pt-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">
                                                {{ __('Customer Information') }}</h4>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-gray-900">{{ $selectedOrder->customer_name }}
                                                </p>
                                                <p class="text-sm text-gray-500">{{ $selectedOrder->customer_phone }}
                                                </p>
                                                @if ($selectedOrder->customer_email)
                                                    <p class="text-sm text-gray-500">
                                                        {{ $selectedOrder->customer_email }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">
                                                {{ __('Order Information') }}</h4>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-gray-900">
                                                    {{ __('Status') }}:
                                                    <span @class([
                                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                        'bg-yellow-100 text-yellow-800' =>
                                                            $selectedOrder->status === \App\Enums\OrderStatus::Pending,
                                                        'bg-blue-100 text-blue-800' =>
                                                            $selectedOrder->status === \App\Enums\OrderStatus::Processing,
                                                        'bg-green-100 text-green-800' =>
                                                            $selectedOrder->status === \App\Enums\OrderStatus::Completed,
                                                        'bg-red-100 text-red-800' =>
                                                            $selectedOrder->status === \App\Enums\OrderStatus::Cancelled,
                                                    ])>
                                                        {{ $selectedOrder->status->name }}
                                                    </span>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ __('Date') }}:
                                                    {{ $selectedOrder->created_at->format('M d, Y H:i') }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ __('Payment Status') }}:
                                                    <span @class([
                                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                        'bg-green-100 text-green-800' => $selectedOrder->is_paid,
                                                        'bg-red-100 text-red-800' => !$selectedOrder->is_paid,
                                                    ])>
                                                        {{ $selectedOrder->is_paid ? __('Paid') : __('Unpaid') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-500">{{ __('Order Items') }}</h4>
                                    <div class="mt-2 divide-y divide-gray-200">
                                        @foreach ($selectedOrder->items as $item)
                                            <div class="py-3 flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $item->quantity }} x {{ number_format($item->price, 2) }}
                                                        DH
                                                    </p>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ number_format($item->quantity * $item->price, 2) }} DH
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-4 border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Subtotal') }}</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($selectedOrder->total_amount, 2) }} DH
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Profit') }}</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($selectedOrder->getProfit(), 2) }} DH
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-base font-medium text-gray-900">{{ __('Total') }}</p>
                                        <p class="text-base font-medium text-gray-900">
                                            {{ number_format($selectedOrder->total_amount, 2) }} DH
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
