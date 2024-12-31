<div>
    <div class="w-full">
        {{-- <x-alerts /> --}}

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
            <button wire:click="executeBulkAction" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                wire:loading.attr="disabled">
                {{ __('Apply') }}
            </button>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <x-table>
                <x-slot name="header">
                    <tr>
                        <x-table.th>
                            <input type="checkbox" wire:model="selectAll">
                        </x-table.th>
                        <x-table.th>{{ __('Order ID') }}</x-table.th>
                        <x-table.th>{{ __('Customer') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Actions') }}</x-table.th>
                    </tr>
                </x-slot>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->orders as $order)
                        <tr>
                            <x-table.td>
                                <input type="checkbox" wire:model="selectedOrders" value="{{ $order->id }}">
                            </x-table.td>
                            <x-table.td>
                                #{{ $order->id }}
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $order->customer_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $order->customer_phone }}
                                </div>
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
                                <span @class([
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800' =>
                                        $order->status === \App\Enums\OrderStatus::Pending,
                                    'bg-blue-100 text-blue-800' =>
                                        $order->status === \App\Enums\OrderStatus::Processing,
                                    'bg-green-100 text-green-800' =>
                                        $order->status === \App\Enums\OrderStatus::Completed,
                                    'bg-red-100 text-red-800' =>
                                        $order->status === \App\Enums\OrderStatus::Cancelled,
                                ])>
                                    {{ $order->status->name }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </x-table.td>
                            <x-table.td>
                                <x-button color="infoOutline" wire:click="viewOrderDetails({{ $order->id }})"
                                    type="button">
                                    {{ __('View') }}
                                </x-button>
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
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7" class="text-center text-gray-500">
                                {{ __('No orders found.') }}
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
        @if ($this->showOrderDetails)
            <x-modal name="order-details" wire:model="showOrderDetails">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium">{{ __('Order Details') }} #{{ $selectedOrder->id }}
                        </h3>
                        <button wire:click="$set('showOrderDetails', false)" class="text-gray-400 hover:text-gray-500">
                            <x-icon name="x" class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Customer Info -->
                        <div class="border-b pb-4">
                            <h4 class="font-medium mb-2">{{ __('Customer Information') }}</h4>
                            <p>{{ $this->selectedOrder->customer_name }}</p>
                            <p>{{ $this->selectedOrder->customer_phone }}</p>
                        </div>

                        <!-- Order Items -->
                        <div>
                            <h4 class="font-medium mb-2">{{ __('Order Items') }}</h4>
                            <div class="space-y-2">

                                @foreach ($this->selectedOrder->items() as $item)
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
                                <p>{{ number_format($this->selectedOrder->total_amount, 2) }} DH</p>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <p>{{ __('Profit') }}</p>
                                <p>{{ number_format($this->selectedOrder->getProfit(), 2) }} DH</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-modal>
        @endif
    </div>
</div>
