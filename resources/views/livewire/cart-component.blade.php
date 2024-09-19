<div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">{{ __('Your Cart') }}</h2>
        <div class="cart-items space-y-4">
            @foreach ($this->cart as $productId => $item)
                <div class="cart-item flex items-center justify-between border p-4 rounded-lg shadow">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-gray-600">{{ number_format($item['price'], 2) }} DH</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" wire:model="cart.{{ $productId }}.quantity"
                            wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                            class="w-16 p-2 border rounded" min="1">
                        <button wire:click="removeFromCart({{ $productId }})"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50">{{ __('Remove') }}</button>
                    </div>
                </div>
            @endforeach
        </div>
        <h3 class="text-xl font-bold mt-4">{{ __('Total') }}: {{ number_format($totalAmount, 2) }} DH</h3>
        <a href="{{ route('checkout') }}"
            class="bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600 inline-block"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
            {{ __('Proceed to Checkout') }}
        </a>
    </div>
</div>
