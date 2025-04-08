<div>
    <div class="container mx-auto px-4 py-8">
        <!-- Notification System (Keep as is) -->
        <div x-data="{
            notifications: [],
            add(message, type = 'info') {
                const id = Date.now();
                this.notifications.push({ id, message, type });
                setTimeout(() => { this.remove(id) }, 4000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(notification => notification.id !== id);
            }
        }" @notify.window="add($event.detail.message, $event.detail.type)"
            class="fixed top-4 right-4 z-50 space-y-3 w-80">
            <template x-for="notification in notifications" :key="notification.id">
                <div x-show="true" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-8" class="relative">
                    <div class="flex justify-between items-center text-white py-4 px-6 text-md rounded-lg relative shadow-lg"
                        :class="{
                            'bg-green-500': notification.type === 'success',
                            'bg-red-500': notification.type === 'error',
                            'bg-yellow-600': notification.type === 'warning',
                            'bg-blue-500': notification.type === 'info'
                        }">
                        <span class="inline-block mr-5 align-middle">
                            <template x-if="notification.type === 'success'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                            <template x-if="notification.type === 'error'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                            <template x-if="notification.type === 'warning'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </template>
                            <template x-if="notification.type === 'info'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                        </span>
                        <div class="px-4 inline-block align-middle flex-1">
                            <span x-text="notification.message"></span>
                        </div>
                        <button type="button" @click="remove(notification.id)"
                            class="text-white hover:text-gray-200 focus:outline-none ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- livewire order track --}}
        <livewire:order-track />

        <!-- Promotional Banner for Juice Composition -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-600 via-red-500 to-yellow-400 shadow-lg mb-8">
            <div class="absolute inset-0 bg-black/25 backdrop-blur-sm"></div>
            <div class="relative flex flex-col md:flex-row items-center justify-between px-6 md:px-10 py-8 md:py-6">
                <div class="text-center md:text-left md:max-w-lg mb-6 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-3 tracking-tight drop-shadow-lg">
                        Craft Your Perfect Juice
                    </h2>
                    <p class="text-orange-100 text-base md:text-lg leading-relaxed drop-shadow">
                        Select from the freshest ingredients and design your custom juice blend with our easy-to-use
                        system. Pure refreshment awaits!
                    </p>
                    <!-- Button moved here for better flow -->
                    <div class="mt-6">
                        <x-button color="warning" type="href"
                            href="{{ route('compose.product', ['productType' => 'fruits']) }}"
                            class="px-8 py-3 bg-white text-orange-600 rounded-xl shadow-lg hover:bg-orange-50 transition duration-300 transform hover:scale-105 hover:shadow-orange-300/50 ring-2 ring-orange-400/80 text-base font-semibold">
                            Make Your Juice Now
                        </x-button>
                    </div>
                </div>

                <div class="relative flex items-center justify-center w-60 h-60 md:w-72 md:h-72">
                    <!-- Base Image -->
                    <img src="{{ asset('images/make-juice.webp') }}" alt="Fresh Juice"
                        class="relative z-10 rounded-full shadow-xl w-full h-full object-cover border-4 border-white/50">

                    <!-- Subtle Glow Effects -->
                    <div
                        class="absolute w-48 h-48 bg-white/10 rounded-full -top-4 -left-4 blur-3xl opacity-80 animate-pulse">
                    </div>
                    <div
                        class="absolute w-32 h-32 bg-yellow-200/10 rounded-full -bottom-4 -right-4 blur-2xl opacity-70 animate-pulse animation-delay-2000">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent rounded-full"></div>
                </div>
            </div>
        </div>
        <!-- Horizontal Category Scroll -->
        <div class="mb-6 pt-4 bg-white sticky top-0 z-20">
            <div class="relative">
                <div
                    class="flex space-x-3 overflow-x-auto pb-4 px-4 scrollbar-thin scrollbar-thumb-orange-300 scrollbar-track-orange-100">
                    <!-- Category buttons -->
                    @foreach ($categories as $categoryItem)
                        <button wire:click="$set('category', '{{ $categoryItem['slug'] }}')"
                            class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 border-2 
                                       {{ $filters['category'] === $categoryItem['slug']
                                           ? 'bg-orange-500 text-white border-orange-500 scale-105 shadow-lg'
                                           : 'bg-white text-gray-700 border-gray-200 hover:bg-orange-50 hover:border-orange-300' }}">
                            {{ $categoryItem['name'] }}
                        </button>
                    @endforeach
                </div>
                <!-- Gradient overlay for scroll indication -->
                <div
                    class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-white to-transparent pointer-events-none">
                </div>
            </div>
        </div>

        <!-- Product Stats & Filters Header -->
        <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm mb-6 py-4 px-4">
            <div class="container mx-auto flex flex-wrap justify-between items-center gap-4">
                <div class="text-sm text-gray-600 flex items-center space-x-4">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5 text-orange-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Showing <span class="font-semibold mx-1">{{ count($products['data']) }}</span> of <span
                            class="font-semibold mx-1">{{ $products['total'] }}</span> meals
                    </span>
                    <!-- Reset Filters Button -->
                    @if ($search || $category || $sort !== 'name')
                        <button wire:click="resetFilters"
                            class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-700 transition-colors flex items-center text-xs font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filters
                        </button>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select wire:model.live="sort" id="sort"
                            class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm transition-all duration-200 appearance-none shadow-sm hover:border-gray-400">
                            <option value="name">{{ __('Name (A-Z)') }}</option>
                            <option value="price_asc">{{ __('Price (Low to High)') }}</option>
                            <option value="price_desc">{{ __('Price (High to Low)') }}</option>
                            <option value="popularity">{{ __('Popularity') }}</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                            </svg>
                        </div>
                    </div>
                    <!-- Search Input -->
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="{{ __('Search meals...') }}"
                            class="w-48 sm:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm transition-all duration-200 shadow-sm hover:border-gray-400" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- View Mode Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1 shadow-sm">
                        <button x-on:click="viewMode = 'grid'" type="button"
                            class="p-2 rounded-md transition-all duration-200"
                            :class="{ 'bg-orange-500 text-white shadow-md': viewMode === 'grid', 'text-gray-500 hover:bg-gray-200': viewMode !== 'grid' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button x-on:click="viewMode = 'list'" type="button"
                            class="p-2 rounded-md transition-all duration-200"
                            :class="{ 'bg-orange-500 text-white shadow-md': viewMode === 'list', 'text-gray-500 hover:bg-gray-200': viewMode !== 'list' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="relative">
            @if (empty($products['data']) && !$this->search && !$this->category) {{-- Check if initial load is empty --}}
                <div
                    class="text-center py-20 px-6 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 shadow-sm">
                    <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12c0 4.142-3.358 7.5-7.5 7.5S4.5 16.142 4.5 12 7.858 4.5 12 4.5s7.5 3.358 7.5 7.5zm-7.5-4.5v4.5m0 4.5h.01" />
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.2" />
                    </svg>
                    <h3 class="text-2xl font-semibold mb-3 text-gray-800">Menu Currently Empty</h3>
                    <p class="text-base max-w-lg mx-auto text-gray-600">
                        We're preparing our delicious offerings. Please check back soon!
                    </p>
                </div>
            @elseif (empty($products['data']))
                {{-- Check if filters result in empty --}}
                <div
                    class="text-center py-20 px-6 rounded-xl bg-gradient-to-br from-yellow-50/50 to-orange-50/50 border border-orange-200/60 shadow-sm">
                    <svg class="w-16 h-16 mx-auto mb-6 text-orange-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.25v-.001" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25v-.001" />
                    </svg>

                    <h3 class="text-2xl font-semibold mb-3 text-gray-800">{{ __('No meals match your criteria') }}
                    </h3>
                    <p class="text-base max-w-lg mx-auto text-gray-600 mb-6">
                        {{ __('Try adjusting your search or filters, or reset them to see all available meals.') }}
                    </p>
                    <button wire:click="resetFilters"
                        class="px-6 py-2.5 bg-orange-500 text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors shadow hover:shadow-md">
                        Reset Filters & View All
                    </button>
                </div>
            @else
                {{-- Skeleton Loader - Show during loading --}}
                <div wire:loading wire:target="category, search, sort, loadMoreProducts, resetFilters"
                    :class="{
                        'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6': viewMode === 'grid',
                        'space-y-4': viewMode === 'list'
                    }"
                    class="w-full">
                    @for ($i = 0; $i < ($products['per_page'] ?? $perPage); $i++)
                        <div class="bg-white rounded-xl shadow-sm animate-pulse border border-gray-100 overflow-hidden"
                            :class="{
                                'flex h-36': viewMode === 'list',
                                'flex flex-col': viewMode === 'grid'
                            }">
                            {{-- Image Skeleton --}}
                            <div class="bg-gray-200"
                                :class="{
                                    'w-1/3 rounded-l-xl flex-shrink-0': viewMode === 'list',
                                    'w-full aspect-square rounded-t-xl': viewMode === 'grid'
                                }">
                            </div>
                            {{-- Content Skeleton --}}
                            <div class="p-4 flex-1 flex flex-col"
                                :class="{
                                    'w-2/3': viewMode === 'list'
                                }">
                                <div class="h-5 bg-gray-200 rounded w-3/4 mb-2"></div> {{-- Name --}}
                                <div class="h-3 bg-gray-200 rounded w-full mb-1"></div> {{-- Desc line 1 --}}
                                <div class="h-3 bg-gray-200 rounded w-5/6 mb-4"></div> {{-- Desc line 2 --}}
                                <div class="mt-auto pt-3 space-y-3">
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div> {{-- Price/Size line --}}
                                    <div class="h-10 bg-gray-300 rounded-lg w-full"></div> {{-- Button --}}
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Actual Product Grid/List --}}
                <div
                    :class="{
                        'grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6': viewMode === 'grid',
                        'gap-y-6': viewMode === 'list'
                    }">
                    @foreach ($products['data'] as $product)
                        <div x-data="{ selectedSize: null, selectedPrice: null }"
                            class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-gray-200 relative transform hover:scale-[1.02]"
                            :class="{ 'flex flex-row h-auto': viewMode === 'list', 'flex flex-col': viewMode === 'grid' }"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-98"
                            x-transition:enter-end="opacity-100 transform scale-100">

                            <!-- Product image -->
                            <div class="relative overflow-hidden flex-shrink-0"
                                :class="{
                                    'w-1/3 max-w-[160px] rounded-l-xl': viewMode === 'list',
                                    'w-full aspect-square rounded-t-xl': viewMode === 'grid'
                                }">
                                <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                                    alt="{{ $product['name'] }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy" />
                                {{-- onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80';"> --}}
                                @if ($product['category'])
                                    <div class="absolute top-2 left-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 shadow-sm">
                                            {{ $product['category']['name'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product details -->
                            <div class="p-4 flex flex-col flex-grow" :class="{ 'w-2/3': viewMode === 'list' }">
                                <h3 class="text-base font-semibold text-gray-800 group-hover:text-orange-600 transition-colors duration-200 mb-1 truncate"
                                    title="{{ $product['name'] }}">
                                    {{ $product['name'] }}
                                </h3>
                                <p class="text-xs text-gray-500 mb-3 line-clamp-2"
                                    :class="{ 'line-clamp-3': viewMode === 'list' }"
                                    title="{{ $product['description'] }}">
                                    {{ $product['description'] }}
                                </p>

                                <!-- Pricing and Actions -->
                                <div class="mt-auto pt-2 space-y-3">
                                    @if ($product['isAvailableForOrder'])
                                        @if (!empty($product['prices']) && count($product['prices']) > 1)
                                            {{-- Multiple Sizes/Prices --}}
                                            <div class="mb-2">
                                                <span class="text-xs font-medium text-gray-500 mr-2 block mb-1">
                                                    Select Size:</span>
                                                <div class="flex justify-between mt-6 mb-4 gap-2">
                                                    @foreach ($product['prices'] as $index => $price)
                                                        <button type="button"
                                                            wire:key="product-{{ $product['id'] }}-size-{{ $price['size'] }}-v3"
                                                            @click="selectedSize = '{{ $price['size'] }}'; selectedPrice = {{ $price['price'] }}"
                                                            :class="{
                                                                'bg-orange-500 text-white ring-orange-300 scale-105 shadow': selectedSize === '{{ $price['size'] }}',
                                                                'bg-gray-100 text-gray-700 hover:bg-gray-200 ring-gray-200 hover:ring-gray-300': selectedSize !== '{{ $price['size'] }}'
                                                            }"
                                                            class="px-3 py-1.5 rounded-md text-xs font-medium focus:z-10 focus:outline-none ring-1 focus:ring-2 focus:ring-offset-1 focus:ring-orange-500 transition: all 0.2s ease;">
                                                            {{ $price['size'] }} -
                                                            {{ number_format($price['price'], 2) }} DH
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div>
                                                <button type="button"
                                                    @click="$dispatch('addToCart', {
                                                         product: {{ json_encode($product) }},
                                                         quantity: 1,
                                                         size: selectedSize,
                                                         price: selectedPrice
                                                     }); $dispatch('notify', { message: 'Added to cart!', type: 'success' }); $dispatch('open-cart')"
                                                    :disabled="!selectedSize"
                                                    class="w-full py-2.5 px-4 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center shadow-sm"
                                                    :class="{
                                                        'bg-orange-500 hover:bg-orange-600 text-white cursor-pointer': selectedSize,
                                                        'bg-gray-300 text-gray-500 cursor-not-allowed opacity-70': !
                                                            selectedSize
                                                    }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span x-text="selectedSize ? 'Add to Cart' : 'Select Size'"></span>
                                                </button>
                                            </div>
                                        @elseif (!empty($product['prices']) && count($product['prices']) === 1)
                                            {{-- Single Price/Size --}}
                                            @php $singlePrice = $product['prices'][0]; @endphp
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-semibold text-orange-600">
                                                    {{ number_format($singlePrice['price'], 2) }} DH
                                                    @if ($singlePrice['size'] !== 'default')
                                                        <span
                                                            class="text-xs text-gray-500 ml-1">({{ $singlePrice['size'] }})</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <button
                                                wire:click="$dispatch('addToCart', {
                                                     product: {{ json_encode($product) }},
                                                     quantity: 1,
                                                     size: '{{ $singlePrice['size'] }}',
                                                     price: {{ $singlePrice['price'] }}
                                                 }); $dispatch('notify', { message: '{{ $product['name'] }} added!', type: 'success' })"
                                                @click="$dispatch('open-cart')"
                                                class="w-full py-2.5 px-4 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold transition-colors flex items-center justify-center shadow hover:shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ __('Add to Cart') }}
                                            </button>
                                        @else
                                            {{-- No specific prices (maybe legacy or error?) - Show base price if exists --}}
                                            @if ($product['price'] > 0)
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-semibold text-orange-600">
                                                        {{ number_format($product['price'], 2) }} DH
                                                    </span>
                                                </div>
                                                <button
                                                    wire:click="$dispatch('addToCart', { product: {{ json_encode($product) }}, quantity: 1, price: {{ $product['price'] }} }); $dispatch('notify', { message: '{{ $product['name'] }} added!', type: 'success' })"
                                                    @click="$dispatch('open-cart')"
                                                    class="w-full py-2.5 px-4 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold transition-colors flex items-center justify-center shadow hover:shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    {{ __('Add to Cart') }}
                                                </button>
                                            @else
                                                {{-- Fallback if no price info at all --}}
                                                <div
                                                    class="w-full py-2.5 px-4 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ __('Info Unavailable') }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        {{-- Product Not Available --}}
                                        <div
                                            class="w-full py-2.5 px-4 bg-red-100 text-red-600 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed border border-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <span>{{ __('Currently Unavailable') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Load More Button -->
            @if ($products['has_more'])
                <div class="mt-10 flex justify-center">
                    <button wire:click="loadMoreProducts" wire:loading.attr="disabled"
                        wire:loading.class="opacity-75 cursor-wait"
                        class="relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg text-base font-semibold shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <span wire:loading.remove wire:target="loadMoreProducts" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:translate-y-0.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            {{ __('Load More Meals') }}
                        </span>
                        <span wire:loading wire:target="loadMoreProducts" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            {{ __('Loading...') }}
                        </span>
                        <!-- Subtle Shine Effect -->
                        <span
                            class="absolute top-0 left-[-100%] w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent opacity-50 group-hover:left-[100%] transition-all duration-700 ease-in-out"></span>
                    </button>
                </div>
            @endif
        </div> <!-- End Products Section -->
    </div> <!-- End Container -->

    <!-- Include CartDrawer component (Keep as is) -->
    <livewire:cart-drawer />

</div>
