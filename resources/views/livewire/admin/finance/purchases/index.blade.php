<div>
    <x-theme.breadcrumb :title="__('Purchase Management')" :parent="route('admin.purchase')" :parentName="__('Purchase Management')" />

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Pending Orders') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['pending_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Completed Orders') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['completed_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Total Amount') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_amount'], 2) }} DH</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Average Order Value') }}</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ number_format($stats['average_order_value'], 2) }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-label for="search" :value="__('Search')" />
                <x-input wire:model.live="search" type="search" placeholder="{{ __('Search orders...') }}"
                    class="w-full" />
            </div>
            <div>
                <x-label for="status_filter" :value="__('Status')" />
                <select wire:model.live="status_filter" class="w-full form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="processing">{{ __('Processing') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div>
                <x-label for="supplier_filter" :value="__('Supplier')" />
                <select wire:model.live="supplier_filter" class="w-full form-select">
                    <option value="">{{ __('All Suppliers') }}</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="date_from" :value="__('From')" />
                    <x-input wire:model.live="date_from" type="date" class="w-full" />
                </div>
                <div>
                    <x-label for="date_to" :value="__('To')" />
                    <x-input wire:model.live="date_to" type="date" class="w-full" />
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Order #') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Supplier') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Items') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Total') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Date') }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $order->reference }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $order->supplier->name }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $order->items->count() }} items</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ number_format($order->total_amount, 2) }} DH
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($order->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <x-button wire:click="viewOrder('{{ $order->id }}')" color="primary"
                                        size="sm">
                                        <span class="material-icons">visibility</span>
                                    </x-button>
                                    @if ($order->status === 'pending')
                                        <x-button wire:click="approveOrder('{{ $order->id }}')" color="success"
                                            size="sm">
                                            <span class="material-icons">check_circle</span>
                                        </x-button>
                                    @endif
                                    @if ($order->status === 'processing')
                                        <x-button wire:click="receiveOrder('{{ $order->id }}')" color="info"
                                            size="sm">
                                            <span class="material-icons">local_shipping</span>
                                        </x-button>
                                    @endif
                                    @if (in_array($order->status, ['pending', 'processing']))
                                        <x-button wire:click="cancelOrder('{{ $order->id }}')" color="danger"
                                            size="sm">
                                            <span class="material-icons">cancel</span>
                                        </x-button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                {{ __('No purchase orders found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Order Details Modal -->
    <x-modal wire:model="showOrderDetails" name="showOrderDetails">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Order Details') }}</h3>
        </x-slot>
        @if ($selectedOrder)
            <div class="space-y-6">
                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-medium text-gray-900 mb-2">{{ __('Order Information') }}</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Order Number') }}</p>
                            <p class="font-medium">{{ $selectedOrder->reference }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($selectedOrder->status === 'completed') bg-green-100 text-green-800
                                @elseif($selectedOrder->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($selectedOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($selectedOrder->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Supplier') }}</p>
                            <p class="font-medium">{{ $selectedOrder->supplier->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Date') }}</p>
                            <p class="font-medium">{{ $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-medium text-gray-900 mb-2">{{ __('Order Items') }}</h4>
                    <div class="space-y-4">
                        @foreach ($selectedOrder->items as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->ingredient->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} {{ $item->unit }} @
                                        {{ number_format($item->unit_price, 2) }} DH</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">
                                        {{ number_format($item->quantity * $item->unit_price, 2) }} DH</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-medium text-gray-900 mb-2">{{ __('Order Summary') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('Subtotal') }}</span>
                            <span class="font-medium">{{ number_format($selectedOrder->subtotal, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('Tax') }}</span>
                            <span class="font-medium">{{ number_format($selectedOrder->tax, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold">
                            <span>{{ __('Total') }}</span>
                            <span>{{ number_format($selectedOrder->total_amount, 2) }} DH</span>
                        </div>
                    </div>
                </div>

                @if ($selectedOrder->notes)
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="font-medium text-gray-900 mb-2">{{ __('Notes') }}</h4>
                        <p class="text-gray-600">{{ $selectedOrder->notes }}</p>
                    </div>
                @endif
            </div>
        @endif
    </x-modal>
</div>
