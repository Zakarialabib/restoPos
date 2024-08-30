<div>
    <div class="container mx-auto p-4" x-data="{ showSuccessModal: @entangle('showSuccessModal') }">
        <h2 class="text-2xl font-bold mb-4 text-black">Checkout</h2>
        <form wire:submit="placeOrder" class="space-y-4">
            <div>
                <label for="customerName" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="customerName" wire:model="customerName" class="mt-1 p-2 border rounded w-full">
                @error('customerName')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="customerPhone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="customerPhone" wire:model="customerPhone"
                    class="mt-1 p-2 border rounded w-full">
                @error('customerPhone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <h3 class="text-xl font-bold mt-4 text-black">Order Summary</h3>
            <div class="ord er-summary space-y-4">
                @foreach ($this->cart as $item)
                    <div class="flex items-center justify-between border px-4">
                        <div class="w-1/2">
                            <h4 class="text-lg font-semibold text-black">{{ $item['name'] }}</h4>
                            <p class="text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                            <p class="text-gray-600">Price: {{ $item['price'] }}</p>
                        </div>
                        <div class="w-1/2">
                            <p class="text-gray-600">Ingredients:</p>
                            <ul class="list-disc list-inside text-gray-600">
                                <li><strong>Fruits:</strong> {{ implode(', ', $item['ingredients']['fruits']) }}</li>
                                <li><strong>Base:</strong> {{ $item['ingredients']['base'] }}</li>
                                <li><strong>Sugar:</strong> {{ $item['ingredients']['sugar'] }}</li>
                                <li><strong>Add-ons:</strong> {{ implode(', ', $item['ingredients']['addons']) }}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
            <h3 class="text-xl font-bold mt-4 text-black">Total:
                {{ array_reduce(
                    $this->cart,
                    function ($carry, $item) {
                        return $carry + $item['price'] * $item['quantity'];
                    },
                    0,
                ) }}DH
            </h3>
            <button type="button" wire:click="placeOrder"
                class="bg-black text-white px-4 py-2 rounded hover:bg-white hover:text-black border border-black transition-colors duration-300">Place
                Order</button>
        </form>


        <!-- Success Modal -->
        <div x-show="showSuccessModal" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    x-show="showSuccessModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    role="dialog" aria-modal="true" x-show="showSuccessModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-y-0"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl font-bold mt-4 text-black">Order Summary</h3>

                                @if ($order)
                                    <div class="mt-2">
                                        <p class="text-gray-700">Thank you for your order,
                                            {{ $order?->customer_name }}!
                                            Here are the details of your order:</p>
                                        <div class="space-y-4">
                                            @foreach ($order->items as $item)
                                                <div class="flex items-center justify-between border px-4">
                                                    <div class="w-1/2">
                                                        <h4 class="text-lg font-semibold text-black">
                                                            {{ $item->name }}
                                                        </h4>
                                                        <p class="text-gray-600">Quantity: {{ $item->quantity }}
                                                        </p>
                                                        <p class="text-gray-600">Price: {{ $item->price }}DH</p>
                                                    </div>
                                                    <div class="w-1/2">
                                                        <p class="text-gray-600">Ingredients:</p>
                                                        <div class="flex flex-col text-left justify-start">
                                                            @php
                                                                $ingredients = $item->details;
                                                            @endphp
                                                            <o><strong>Fruits:</strong>
                                                                {{ implode(', ', $ingredients['fruits']) }}</o>
                                                            <o><strong>Base:</strong> {{ $ingredients['base'] }}
                                                            </o>
                                                            <o><strong>Sugar:</strong> {{ $ingredients['sugar'] }}
                                                            </o>
                                                            <o><strong>Add-ons:</strong>
                                                                {{ implode(', ', $ingredients['addons']) }}</o>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <h3 class="text-xl font-bold mt-4 text-black">Total:
                                            {{ $order?->total_amount }}DH
                                        </h3>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeSuccessModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
