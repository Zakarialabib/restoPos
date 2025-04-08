<div>
    {{-- Cart Drawer --}}
    <div x-data="{ isOpen: false, isSubmitting: false, isLoading: true, showClearCartDialog: false }" x-init="$watch('isOpen', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
            setTimeout(() => isLoading = false, 800);
        } else {
            document.body.classList.remove('overflow-hidden');
            isLoading = true;
        }
    });" @open-cart.window="isOpen = true"
        @close-cart.window="isOpen = false" @keydown.escape.window="isOpen = false">

        {{-- Backdrop --}}
        <div x-show="isOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="isOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40" x-cloak>
        </div>

        {{-- Drawer --}}
        <div x-show="isOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-xl z-50 flex flex-col" x-cloak>

            {{-- Header --}}
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Your Cart</h2>
                        <span class="text-sm text-gray-500">
                            <span x-text="$wire.cartItems.length"></span> items in your cart
                        </span>
                    </div>
                </div>
                <button @click="isOpen = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto p-5 bg-gray-50">
                <template x-if="isLoading">
                    <div class="space-y-4">
                        <div
                            class="animate-pulse flex gap-4 bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-gray-100">
                            <div class="w-20 h-20 bg-gray-200 rounded-xl flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="space-y-2 flex-1">
                                        <div class="h-5 bg-gray-200 rounded-md w-3/4"></div>
                                        <div class="h-4 bg-gray-200 rounded-md w-1/4"></div>
                                        <div class="h-4 bg-gray-200 rounded-md w-1/3"></div>
                                    </div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                </div>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="animate-pulse flex gap-4 bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-gray-100">
                            <div class="w-20 h-20 bg-gray-200 rounded-xl flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="space-y-2 flex-1">
                                        <div class="h-5 bg-gray-200 rounded-md w-3/4"></div>
                                        <div class="h-4 bg-gray-200 rounded-md w-1/4"></div>
                                        <div class="h-4 bg-gray-200 rounded-md w-1/3"></div>
                                    </div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                </div>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="!isLoading && $wire.cartItems.length === 0">
                    <div class="text-center py-16 px-4">
                        <div class="w-24 h-24 mx-auto mb-6 bg-orange-50 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-orange-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Your cart is empty</h3>
                        <p class="text-gray-500 mb-6 max-w-xs mx-auto">Add some delicious items to your cart and they
                            will appear here.</p>
                        <x-button color="warning" size="md" @click="isOpen = false" class="mx-auto"
                            icon="arrow-left">
                            Continue Shopping
                        </x-button>
                    </div>
                </template>

                <template x-if="!isLoading && $wire.cartItems.length > 0">
                    <div class="space-y-4">
                        <template x-for="(item, index) in $wire.cartItems" :key="`${item.id}-${item.size}`">
                            <div
                                class="group flex gap-4 bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-gray-100 hover:border-orange-100 transition-all duration-200">
                                <!-- Product Image -->
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 relative">
                                    <template x-if="item.image">
                                        <img :src="item.image" :alt="item.name"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            loading="lazy"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center bg-orange-50">
                                            <span class="text-orange-500 text-2xl font-bold"
                                                x-text="item.name[0]"></span>
                                        </div>
                                    </template>

                                    <!-- Size badge if applicable -->
                                    <template x-if="item.size">
                                        <div class="absolute top-1 left-1">
                                            <span
                                                class="text-xs font-medium px-1.5 py-0.5 bg-orange-500/80 text-white rounded-md backdrop-blur-sm"
                                                x-text="item.size"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-orange-600 transition-colors duration-200"
                                                x-text="item.name"></h3>
                                            <div class="text-orange-600 font-medium mt-1"
                                                x-text="`${(parseFloat(item.price) * item.quantity).toFixed(2)} DH`">
                                            </div>
                                        </div>
                                        <button @click="$wire.removeFromCart(item.id, item.size)"
                                            class="text-gray-400 hover:text-red-500 transition-colors p-1.5 hover:bg-red-50 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mt-3 flex items-center gap-2">
                                        <button
                                            @click="$wire.updateQuantity(item.id, item.size, Math.max(1, item.quantity - 1))"
                                            :disabled="item.quantity <= 1"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center font-medium text-gray-800"
                                            x-text="item.quantity"></span>
                                        <button @click="$wire.updateQuantity(item.id, item.size, item.quantity + 1)"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <template x-if="!isLoading && $wire.cartItems.length > 0">
                <div class="border-t border-gray-100 p-5 bg-white">
                    <div class="mb-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-800" x-text="`${$wire.cartTotal.toFixed(2)} DH`"></span>
                        </div>
                        <div class="flex justify-between font-semibold text-lg pt-3 border-t border-gray-100">
                            <span class="text-gray-800">Total</span>
                            <span class="text-orange-600" x-text="`${$wire.cartTotal.toFixed(2)} DH`"></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button color="warning" size="md"
                            class="bg-orange-500 text-white hover:bg-orange-600 focus:ring-orange-500 active:bg-orange-900 focus:outline-none focus:border-orange-900 w-full justify-center shadow-lg shadow-orange-500/20 py-3 rounded-lg font-medium"
                            @click="isSubmitting = true; $wire.placeOrder().then(() => { isSubmitting = false; isOpen = false; })"
                            :disabled="isSubmitting">
                            <template x-if="isSubmitting">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" fill="none" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Processing...
                                </span>
                            </template>
                            <template x-if="!isSubmitting">
                                <span class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span x-text="`Place Order (${$wire.cartTotal.toFixed(2)} DH)`"></span>
                                </span>
                            </template>
                        </button>

                        <!-- Test button - remove in production -->
                        <x-button color="secondaryOutline" size="md" class="w-full justify-center mt-3"
                            wire:click="simulateOrder" @click="isOpen = false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Test Order Tracking
                        </x-button>

                        <x-button color="secondaryOutline" size="md" class="w-full justify-center"
                            @click="showClearCartDialog = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear Cart
                        </x-button>
                    </div>
                </div>
            </template>

            <!-- Clear Cart Confirmation Dialog -->
            <div x-show="showClearCartDialog" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
                <div x-show="showClearCartDialog" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @click.away="showClearCartDialog = false"
                    class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Clear your cart?</h3>
                        <p class="text-gray-500 mb-6">This will remove all items from your cart. This action cannot be
                            undone.</p>
                        <div class="flex gap-3">
                            <x-button color="secondaryOutline" size="sm" class="flex-1 justify-center"
                                @click="showClearCartDialog = false">
                                Cancel
                            </x-button>
                            <x-button color="danger" size="sm" class="flex-1 justify-center"
                                @click="$wire.clearCart(); showClearCartDialog = false">
                                Clear Cart
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ui not being Floating Basket Button -->
    <div x-show="$wire.cartItems.length > 0 && !isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-10" @click="isOpen = true"
        class="fixed bottom-6 right-6 z-50 basket-button-float cursor-pointer">
        <div
            class="flex items-center bg-gradient-to-r from-orange-500 to-red-500 text-white px-5 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="relative mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="absolute -top-2 -right-2 bg-white text-orange-600 text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center cart-badge-pulse"
                    x-text="$wire.cartItems.length"></span>
            </div>
            <div class="flex flex-col">
                <span class="text-xs font-medium">Your Cart</span>
                <span class="text-sm font-bold" x-text="`$${$wire.cartTotal.toFixed(2)}`"></span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-3" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </div>
    </div>
</div>
