<div>
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container mx-auto px-4 py-8" x-data="{ showModal: @entangle('showModal') }">
        <!-- Search and Category Filter -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="w-full md:w-1/3">
                    <input wire:model.live.debounce.300ms="searchQuery" type="text"
                        placeholder="{{ __('Search products...') }}"
                        class="w-full ps-2 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div class="w-full flex gap-2 overflow-x-auto pb-2">
                    <button wire:click="selectedCategory(null)"
                        class="px-4 py-2 rounded-full whitespace-nowrap {{ is_null($selectedCategory) ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                        {{ __('All') }}
                    </button>
                    @foreach ($this->categories as $category)
                        <button wire:click="selectedCategory('{{ $category->id }}')"
                            class="px-4 py-2 rounded-full whitespace-nowrap {{ $selectedCategory === $category->id ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($this->products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if (!$product->is_available)
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
                        <!-- Customization Options -->
                        @if ($product?->customization_options)
                            @foreach ($product->customization_options as $category => $options)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-2">{{ __($category) }}</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach ($options as $option)
                                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                                <div>
                                                    <span class="font-medium">{{ $option['name'] }}</span>
                                                    <span
                                                        class="text-sm text-gray-500">+{{ number_format($option['price'], 2) }}
                                                        DH</span>
                                                </div>
                                                <input type="number"
                                                    wire:model="selectedOptions.{{ $option['id'] }}.quantity"
                                                    min="0" max="{{ $option['max_quantity'] ?? 5 }}"
                                                    class="w-16 text-center border rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Quantity and Total -->
                        <span class="text-xl font-bold">
                            {{ number_format($this->getProductPrice($product, $selectedSize, 'default'), 2) ?? 0 }} DH
                        </span>

                        <div class="flex gap-2 mb-2">
                            @foreach ($product->getAvailableSizes() as $size)
                                <button wire:click="$set('selectedSize', '{{ $size->metadata['size'] }}')"
                                    class="px-3 py-1 rounded-full text-sm {{ $selectedSize === $size->metadata['size'] ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                                    {{ __(ucfirst($size->metadata['size'])) }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex flex-col gap-y-2 my-2 items-center">
                            <div class="flex items-center gap-x-2">
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200"
                                    wire:click="$set('quantity', Math.max(1, quantity - 1))">
                                    <span class="material-symbols">-</span>
                                </button>
                                <input type="number" wire:model="quantity" min="1" max="10"
                                    class="w-16 py-2 text-center border rounded" readonly>
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200"
                                    wire:click="$set('quantity', Math.min(10, quantity + 1))">
                                    <span class="material-symbols">+</span>
                                </button>
                            </div>
                        </div>
                        <hr class="w-full my-2">

                        <div class="flex flex-row gap-y-2 items-center">
                            <x-button color="primary" wire:click="addToCart('{{ $product->id }}')">
                                {{ __('Add to Cart') }}
                            </x-button>
                            {{-- seee ingredients in sort of modal --}}
                            <x-button color="secondary" wire:click="$set('showModal', true)">
                                <span class="material-symbols mr-2">
                                </span> {{ __('Read more') }}
                            </x-button>
                        </div>
                    </div>
                    @if ($this->product)
                        <x-modal wire:model="showModal">
                            <x-slot name="title">
                                {{ __('Ingredients') }}
                            </x-slot>
                            <x-slot name="content">
                                <p>{{ $this->product?->ingredients }}</p>
                            </x-slot>
                        </x-modal>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">{{ __('No products found.') }}</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
