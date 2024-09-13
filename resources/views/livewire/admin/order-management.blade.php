<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">{{ __('Order Management') }}</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="mb-4">
                    <button wire:click="toggleOrderForm"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ $showOrderForm ? 'Cancel' : 'Create New Order' }}
                    </button>
                </div>

                @if ($showOrderForm)
                    <div class="mb-8 bg-gray-100 p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Create New Order</h3>
                        <form wire:submit.prevent="createOrder">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="customerName" class="block text-sm font-medium text-gray-700">Customer
                                        Name</label>
                                    <input type="text" id="customerName" wire:model="customerName"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('customerName')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="customerPhone" class="block text-sm font-medium text-gray-700">Customer
                                        Phone</label>
                                    <input type="text" id="customerPhone" wire:model="customerPhone"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('customerPhone')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-md font-semibold mb-2">Order Items</h4>
                                @foreach ($orderItems as $index => $item)
                                    <div class="flex items-center space-x-2 mb-2">
                                        <select wire:model="orderItems.{{ $index }}.product_id"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select a product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} -
                                                    ${{ $product->price }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" wire:model="orderItems.{{ $index }}.quantity"
                                            min="1"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <button type="button" wire:click="removeOrderItem({{ $index }})"
                                            class="bg-red-500 text-white px-2 py-1 rounded">Remove</button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addOrderItem"
                                    class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add
                                    Item</button>
                            </div>

                            <div>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create
                                    Order</button>
                            </div>
                        </form>
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ $order->total_amount }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-100 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="viewOrderDetails({{ $order->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">View Details</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>

                @if ($showOrderDetails)
                    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Order
                                        Details</h3>
                                    <div class="mt-2">
                                        <p><strong>Order ID:</strong> {{ $selectedOrder->id }}</p>
                                        <p><strong>Customer:</strong> {{ $selectedOrder->customer_name }}</p>
                                        <p><strong>Phone:</strong> {{ $selectedOrder->customer_phone }}</p>
                                        <p><strong>Total:</strong> ${{ $selectedOrder->total_amount }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($selectedOrder->status) }}</p>
                                        <h4 class="font-medium mt-4">Order Items:</h4>
                                        <ul>
                                            @foreach ($selectedOrder->items as $item)
                                                <li>{{ $item->product->name }} - Quantity: {{ $item->quantity }} -
                                                    ${{ $item->price * $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button wire:click="updateOrderStatus({{ $selectedOrder->id }}, 'completed')"
                                        type="button"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Mark as Completed
                                    </button>
                                    <button wire:click="updateOrderStatus({{ $selectedOrder->id }}, 'cancelled')"
                                        type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel Order
                                    </button>
                                    <button wire:click="$set('showOrderDetails', false)" type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
