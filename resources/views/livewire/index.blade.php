<div>
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <!-- Search and Category Filter -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="w-full md:w-1/3">
                    <input wire:model.live.debounce.300ms="searchQuery" type="text"
                        placeholder="{{ __('Search products...') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div class="flex gap-2 overflow-x-auto pb-2">
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
                <livewire:components.product-card :product="$product" :key="$product->id" />
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">{{ __('No products found.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
