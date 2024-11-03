<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow-lg">
                <!-- Header with Analytics -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ __('Order Management') }}</h2>
                            <p class="text-sm text-gray-600">{{ __('Manage and track orders') }}</p>
                        </div>
                        <button wire:click="$toggle('showAnalytics')"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            {{ $showAnalytics ? __('Hide Analytics') : __('Show Analytics') }}
                        </button>
                    </div>

                    @if ($showAnalytics)
                        <div class="grid grid-cols-4 gap-4 mb-6">
                            @foreach ($this->orderAnalytics as $key => $value)
                                <div class="bg-white p-4 rounded-lg shadow border">
                                    <h3 class="text-sm font-medium text-gray-500">
                                        {{ Str::title(str_replace('_', ' ', $key)) }}
                                    </h3>
                                    <p class="text-2xl font-bold text-gray-900">
                                        @if (str_contains($key, 'revenue') || str_contains($key, 'profit'))
                                            {{ number_format($value, 2) }} DH
                                        @else
                                            {{ $value }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Filters and Search -->
                <div class="mb-6 grid grid-cols-4 gap-4">
                    <div>
                        <input type="text" wire:model.live="search" class="w-full rounded border-gray-300"
                            placeholder="{{ __('Search orders...') }}">
                    </div>
                    <div>
                        <select wire:model.live="selectedStatus" class="w-full rounded border-gray-300">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach (App\Enums\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" wire:model="dateRange" x-data x-init="flatpickr($el, { mode: 'range' })"
                            class="w-full rounded border-gray-300" placeholder="{{ __('Date Range') }}">
                    </div>
                    <div>
                        <button wire:click="$toggle('showOrderForm')"
                            class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            {{ __('New Order') }}
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="mb-4 flex items-center gap-4">
                    <select wire:model="bulkAction" class="rounded border-gray-300">
                        <option value="">{{ __('Bulk Actions') }}</option>
                        @foreach (App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ __('Mark as ') . $status->name }}</option>
                        @endforeach
                    </select>
                    <button wire:click="executeBulkAction"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" wire:loading.attr="disabled">
                        {{ __('Apply') }}
                    </button>
                </div>

                <!-- Orders Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" wire:model="selectAll">
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Order ID') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Customer') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Total') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" wire:model="selectedOrders" value="{{ $order->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $order->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->customer_phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($order->total_amount, 2) }} DH
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ __('Profit') }}: {{ number_format($order->getProfit(), 2) }} DH
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span @class([
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-yellow-100 text-yellow-800' => $order->status === OrderStatus::Pending,
                                            'bg-blue-100 text-blue-800' => $order->status === OrderStatus::Processing,
                                            'bg-green-100 text-green-800' => $order->status === OrderStatus::Completed,
                                            'bg-red-100 text-red-800' => $order->status === OrderStatus::Cancelled,
                                        ])>
                                            {{ $order->status->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="viewOrderDetails({{ $order->id }})"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            {{ __('View') }}
                                        </button>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                                                {{ __('Status') }} ▼
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                <div class="py-1">
                                                    @foreach (App\Enums\OrderStatus::cases() as $status)
                                                        <button
                                                            wire:click="updateOrderStatus({{ $order->id }}, '{{ $status->value }}')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                                            {{ $status->name }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No orders found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>

                <!-- Order Details Modal -->
                @if ($showOrderDetails && $selectedOrder)
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                        <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-medium">{{ __('Order Details') }} #{{ $selectedOrder->id }}
                                </h3>
                                <button wire:click="$set('showOrderDetails', false)"
                                    class="text-gray-400 hover:text-gray-500">
                                    <x-icon name="x" class="w-6 h-6" />
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Customer Info -->
                                <div class="border-b pb-4">
                                    <h4 class="font-medium mb-2">{{ __('Customer Information') }}</h4>
                                    <p>{{ $selectedOrder->customer_name }}</p>
                                    <p>{{ $selectedOrder->customer_phone }}</p>
                                </div>

                                <!-- Order Items -->
                                <div>
                                    <h4 class="font-medium mb-2">{{ __('Order Items') }}</h4>
                                    <div class="space-y-2">
                                        @foreach ($selectedOrder->items as $item)
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="font-medium">{{ $item->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $item->quantity }} x
                                                        {{ number_format($item->price, 2) }} DH</p>
                                                </div>
                                                <p class="font-medium">
                                                    {{ number_format($item->price * $item->quantity, 2) }} DH</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center font-medium">
                                        <p>{{ __('Total Amount') }}</p>
                                        <p>{{ number_format($selectedOrder->total_amount, 2) }} DH</p>
                                    </div>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <p>{{ __('Profit') }}</p>
                                        <p>{{ number_format($selectedOrder->getProfit(), 2) }} DH</p>
                                    </div>
                                </div>

                                <!-- Stock Validation -->
                                @if (!$this->validateOrder($selectedOrder))
                                    <div class="bg-red-50 border border-red-200 rounded p-4 mt-4">
                                        <p class="text-red-700">
                                            {{ __('Warning: Insufficient stock for some items in this order.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
