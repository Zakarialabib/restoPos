<div>
    <x-theme.breadcrumb :title="__('Inventory Management')" :parent="route('admin.inventory')" :parentName="__('Inventory Management')" />

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stats-card 
            title="Total Ingredients" 
            value="{{ $stats['total_ingredients'] }}" 
            icon="inventory_2" 
            iconColor="blue">
        </x-stats-card>

        <x-stats-card 
            title="Low Stock Items" 
            value="{{ $stats['low_stock_count'] }}" 
            icon="warning" 
            iconColor="yellow">
        </x-stats-card>

        <x-stats-card 
            title="Pending Orders" 
            value="{{ $stats['pending_orders'] }}" 
            icon="pending_actions" 
            iconColor="green">
        </x-stats-card>

        <x-stats-card 
            title="Monthly Purchases" 
            value="${{ number_format($stats['monthly_purchase_amount'], 2) }}" 
            icon="payments" 
            iconColor="purple">
        </x-stats-card>
    </div>

    <!-- Low Stock Alert -->
    @if (count($lowStockItems) > 0)
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Low Stock Alert</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($lowStockItems as $item)
                                <li>{{ $item['ingredient'] }} - Current: {{ $item['current_quantity'] }}
                                    {{ $item['unit'] }} (Reorder Point: {{ $item['reorder_point'] }}
                                    {{ $item['unit'] }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Purchase Orders -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Recent Purchase Orders</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($recent_purchase_orders as $order)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Order #{{ $order->id }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($order->status === 'approved') bg-green-100 text-green-800
                                                            @elseif($order->status === 'received') bg-blue-100 text-blue-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $order->supplier->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        {{ $order->items->count() }} items
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-2">
                                @if ($order->status === 'pending')
                                    <button wire:click="approvePurchaseOrder('{{ $order->id }}')"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Approve
                                    </button>
                                @elseif($order->status === 'approved')
                                    <button wire:click="receivePurchaseOrder('{{ $order->id }}')"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Receive
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-sm text-gray-500">No recent purchase orders</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Current Stock Table -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Current Stock</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ingredient
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Current Stock
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Minimum Quantity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($stocks as $stock)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $stock->ingredient->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $stock->quantity }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $stock->unit }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $stock->minimum_quantity }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <x-button primary type="button"
                                    wire:click="addToPurchaseOrder('{{ $stock->ingredient->id }}')">
                                    Add to Purchase Order
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $stocks->links() }}
        </div>
    </div>

    <!-- Purchase Order Modal -->
    <x-modal wire:model="showPurchaseOrderModal" name="showPurchaseOrderModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Create Purchase Order') }}</h3>
        </x-slot>
        <div class="space-y-6">
            <!-- Supplier Selection -->
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-medium text-gray-900 mb-2">{{ __('Supplier Information') }}</h4>
                <div class="space-y-4">
                    <div>
                        <x-label for="selectedSupplier" :value="__('Select Supplier')" />
                        <select id="selectedSupplier" wire:model="selectedSupplier" class="w-full form-select">
                            <option value="">{{ __('Choose a supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedSupplier')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-medium text-gray-900 mb-2">{{ __('Order Items') }}</h4>
                <div class="space-y-4">
                    @forelse($purchaseOrderItems as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item['ingredient']->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item['quantity'] }}
                                    {{ $item['ingredient']->unit }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="removeFromPurchaseOrder('{{ $item['ingredient']->id }}')"
                                    class="text-red-500 hover:text-red-700">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('No items added to order') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-medium text-gray-900 mb-2">{{ __('Additional Notes') }}</h4>
                <div>
                    <x-textarea wire:model="notes" class="w-full" rows="3"
                        placeholder="{{ __('Add any special instructions or notes...') }}" />
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <x-button wire:click="$set('showPurchaseOrderModal', false)" color="secondary" type="button">
                    {{ __('Cancel') }}
                </x-button>
                <x-button wire:click="createPurchaseOrder" color="primary" type="button">
                    {{ __('Create Order') }}
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
