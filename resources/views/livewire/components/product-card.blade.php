<?php

use function Livewire\Volt\{state, computed};

state(['product']);
state(['selectedSize' => 'medium']);
state(['quantity' => 1]);


$price = computed(function () {
    return collect($this->product->prices)
        ->firstWhere('size', $this->selectedSize)['price'] ?? 0;
});

$sizes = computed(function () {
    return collect($this->product->prices)->pluck('size')->unique();
});

$addToCart = function () {
    $this->dispatch('addToCart', $this->product->id, $this->selectedSize, $this->quantity, $this->selectedOptions);
};
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if(!$product->is_available)
    <div class="absolute top-2 left-2 ">
        <x-badge color="danger" />
        {{ __('Not Available') }}
    </div>
    @ elseif($product->is_available)
    <div class="absolute top-2 top-l ">
        <x-badge color="success">
            {{ __('Is available') }}
        </x-badge>
    </div>
    @endif

    <div class="px-4 py-6 flex flex-col justify-around items-center">
        <h3 class="text-lg text-center font-semibold mb-2">{{ $product->name }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ $product->description }}</p>
        <hr class="w-full my-2">

        @if($this->sizes->count() > 1)
        <div class="flex gap-2 mb-4">
            @foreach($sizes as $size)
            <button
                wire:click="$set('selectedSize', '{{ $size }}')"
                class="px-3 py-1 rounded-full text-sm {{ $selectedSize === $size ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                {{ __(ucfirst($size)) }}
            </button>
            @endforeach
        </div>
        @endif
        <!-- Customization Options -->
        @if($this->product?->customization_options)
        @foreach($this->product->customization_options as $category => $options)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">{{ __($category) }}</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($options as $option)
                <div class="flex items-center justify-between p-3 border rounded-lg">
                    <div>
                        <span class="font-medium">{{ $option['name'] }}</span>
                        <span class="text-sm text-gray-500">+{{ number_format($option['price'], 2) }} DH</span>
                    </div>
                    <input
                        type="number"
                        wire:model="selectedOptions.{{ $option['id'] }}.quantity"
                        min="0"
                        max="{{ $option['max_quantity'] ?? 5 }}"
                        class="w-16 text-center border rounded">
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        @endif

        <!-- Quantity and Total -->
        <span class="text-xl font-bold">{{ number_format($this->price, 2) }} DH</span>
        <hr class="w-full my-4">

        <div class="flex flex-col gap-y-2 items-center">
            <label class="font-medium">{{ __('Quantity') }}</label>
            <input
                type="number"
                wire:model="quantity"
                min="1"
                max="10"
                class="w-20 ml-2 py-2 mb-4 text-center border rounded">
        </div>
        <x-button color="primary" wire:click="addToCart">
            {{ __('Add to Cart') }}
        </x-button>
    </div>
</div>