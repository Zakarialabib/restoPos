<div>
    <!-- Flash Messages -->
    <div class="my-2 container mx-auto">
        @if (session()->has('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols text-green-400">
                            check_circle
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }} 🎉
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols text-red-400">
                            error
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }} ❌
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="min-h-screen mx-auto mb-6" x-data="{
            step: @entangle('step'),
            selectedSugar: @entangle('selectedSugar'),
            selectedBase: @entangle('selectedBase'),
            selectedAddons: @entangle('selectedAddons'),
            selectedIngredients: @entangle('selectedIngredients'),
            cart: @entangle('cart'),
            selectedSize: @entangle('selectedSize'),
            showSuccess: @entangle('showSuccess'),
            order: @entangle('order'),
        }">
            <!-- Category Navigation -->
            {{-- <div class="p-6">
                <div class="flex justify-center gap-4 flex-wrap">
                    @foreach ($this->availableCategories as $category)
                        <a href="{{ route('composable.product', $category->slug) }}"
                            class="px-6 py-3 text-xl font-bold rounded-full transition-all duration-300 transform hover:scale-105
                              {{ $category->slug === $this->selectedCategorySlug
                                  ? 'bg-retro-orange text-white scale-110'
                                  : 'bg-white text-retro-blue hover:bg-retro-cream' }}
                              shadow-xl border-4 border-retro-orange">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div> --}}

            <!-- Main Content -->
            <div class="mt-4">
                <!-- Steps Navigation -->
                <nav
                    class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-8 justify-center px-4 md:px-8">
                    @foreach ($this->steps as $index => $stepName)
                        <div class="w-full flex-grow relative">
                            <button wire:click="$set('step', {{ $index + 1 }})" type="button"
                                class="w-full flex flex-col items-center gap-2 md:gap-4 px-3 md:px-6 py-2 rounded-2xl transition-all duration-300 transform hover:scale-105
                                @if ($step === $index + 1) bg-retro-orange text-white
                                @elseif($step > $index + 1) bg-green-500 text-white
                                @else bg-white text-retro-blue hover:bg-retro-cream @endif
                                shadow-xl border-4 border-retro-orange">

                                <!-- Larger Step Emoji/Icon -->
                                <span class="text-3xl md:text-6xl mb-1 md:mb-2">
                                    @switch($index + 1)
                                        @case(1)
                                            🍎
                                        @break

                                        @case(2)
                                            🥤
                                        @break

                                        @case(3)
                                            🍯
                                        @break

                                        @case(4)
                                            ✨
                                        @break

                                        @case(5)
                                            📏
                                        @break
                                    @endswitch
                                </span>

                                <!-- Larger Step Number -->
                                <span class="text-sm md:text-xl font-bold">Step {{ $index + 1 }}</span>

                                <!-- Larger Step Name -->
                                <span
                                    class="text-xs md:text-lg text-center font-medium hidden sm:block">{{ $stepName }}</span>

                                <!-- Larger Completion Check -->
                                @if ($step > $index + 1)
                                    <span
                                        class="absolute -top-2 -right-2 md:-top-3 md:-right-3 bg-green-500 rounded-full p-1 md:p-3 text-white text-lg md:text-2xl">
                                        ✓
                                    </span>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </nav>

                <!-- Main Content Area -->
                <div class="flex flex-col lg:flex-row gap-4 md:gap-8 my-2 px-4 md:px-6 py-2">
                    <!-- Main Content: Steps -->
                    <div
                        class="lg:w-3/4 bg-white order-1 border-retro-orange border-solid border-4 rounded-lg px-4 md:px-6 py-4 md:py-6">
                        <!-- Step Content -->
                        <div>
                            <!-- Step 1: Select Ingredients -->
                            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">
                                <h3 class="text-2xl md:text-4xl font-bold text-retro-blue text-center m-0">
                                    {{ __("Select Your {$this->selectedCategory->name} Ingredients") }}
                                    {!! $this->configuration?->getIcon('product') !!}
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
                                    @foreach ($this->availableIngredients as $ingredient)
                                        <div wire:key="ingredient-{{ $ingredient->id }}"
                                            class="transform transition-all duration-300 hover:scale-105 flex flex-col">
                                            <div class="relative bg-white p-3 md:p-6 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange flex-grow
                                            {{ in_array($ingredient->id, $this->selectedIngredients) ? 'ring-8 ring-retro-orange' : '' }}"
                                                wire:click="toggleIngredient('{{ $ingredient->id }}')">

                                                <!-- Ingredient Image -->
                                                <div class="w-full h-32 md:h-48 mb-3 md:mb-4 flex items-center justify-center overflow-hidden rounded-xl bg-gray-100">
                                                    @if($ingredient->image)
                                                        <img src="{{ asset('storage/' . $ingredient->image) }}" alt="{{ $ingredient->name }}" class="object-cover w-full h-full">
                                                    @else
                                                        <span class="text-4xl md:text-6xl text-gray-400">{{ $this->configuration?->getIcon('ingredient') ?? '🍎' }}</span> {{-- Fallback icon --}}
                                                    @endif
                                                </div>

                                                <div class="text-center">
                                                    <h4
                                                        class="text-lg md:text-2xl font-bold text-retro-blue mb-1 md:mb-2">
                                                        {{ $ingredient->name }}</h4>
                                                    @if ($ingredient->portion_size)
                                                        <p class="text-sm md:text-lg text-gray-600 mb-1 md:mb-2">
                                                            {{ $ingredient->portion_size }}
                                                            {{ $ingredient->portion_unit }}
                                                        </p>
                                                    @endif
                                                </div>

                                                @if (in_array($ingredient->id, $this->selectedIngredients))
                                                    <span
                                                        class="absolute top-2 right-2 md:top-4 md:right-4 text-2xl md:text-4xl">✓</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-center space-y-2 md:space-y-4 mt-4 md:mt-8">
                                    <p
                                        class="text-retro-blue text-sm md:text-lg py-2 md:py-4 bg-retro-cream/20 rounded-lg">
                                        {{ __("Can't find what you're looking for? Try searching below!") }} 🔍
                                    </p>
                                    <div class="max-w-md mx-auto">
                                        <input type="text" wire:model.live="search"
                                            class="w-full py-2 md:py-3 px-4 md:px-6 border-4 border-retro-orange rounded-full text-gray-800 bg-white placeholder-gray-600 focus:outline-none focus:ring-4 focus:ring-retro-orange"
                                            placeholder="{{ __('Search ingredients...') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Base Selection -->
                            @if ($this->configuration?->has_base)
                                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-x-10"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-x-0"
                                    x-transition:leave-end="opacity-0 transform -translate-x-10">
                                    <h3 class="text-4xl font-bold mb-8 text-retro-blue text-center">
                                        {{ __('Select Your Base') }} {!! $this->configuration?->getIcon('base') !!}
                                    </h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                                        @foreach ($this->availableBases as $base)
                                            <div class="transform transition-all duration-300 hover:scale-105">
                                                <div class="relative bg-white p-6 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange
                                                {{ $this->selectedBase === $base->id ? 'ring-8 ring-retro-orange' : '' }}"
                                                    wire:click="selectBase('{{ $base->id }}')">

                                                    <div class="text-center">
                                                        <span class="text-8xl mb-6 block">{!! $this->configuration?->getIcon('base') !!}</span>
                                                        <h4 class="text-2xl font-bold text-retro-blue">
                                                            {{ $base->name }}
                                                        </h4>
                                                        @if ($base->portion_size)
                                                            <p class="text-lg text-gray-600 mb-2">
                                                                {{ $base->portion_size }} {{ $base->portion_unit }}
                                                            </p>
                                                        @endif
                                                        <p class="text-xl font-semibold text-retro-orange">
                                                            {{ $base->prices->first()?->price ?? 0 }} DH
                                                        </p>
                                                    </div>

                                                    @if ($this->selectedBase === $base->id)
                                                        <span class="absolute top-4 right-4 text-4xl">✓</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Sugar Selection -->
                            @if ($this->configuration?->has_sugar)
                                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-x-10"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-x-0"
                                    x-transition:leave-end="opacity-0 transform -translate-x-10">
                                    <h3 class="text-4xl font-bold mb-8 text-retro-blue text-center">
                                        {{ __('Sugar Preference') }} {!! $this->configuration?->getIcon('sugar') !!}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                                        @foreach ($this->availableSugars as $sugar)
                                            <div class="transform transition-all duration-300 hover:scale-105">
                                                <div class="relative bg-white p-6 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange
                                                {{ $this->selectedSugar === $sugar->id ? 'ring-8 ring-retro-orange' : '' }}"
                                                    wire:click="selectSugar('{{ $sugar->id }}')">

                                                    <div class="text-center">
                                                        <span class="text-8xl mb-6 block">{!! $this->configuration?->getIcon('sugar') !!}</span>
                                                        <h4 class="text-2xl font-bold text-retro-blue">
                                                            {{ $sugar->name }}
                                                        </h4>
                                                        @if ($sugar->portion_size)
                                                            <p class="text-lg text-gray-600 mb-2">
                                                                {{ $sugar->portion_size }} {{ $sugar->portion_unit }}
                                                            </p>
                                                        @endif
                                                        <p class="text-xl font-semibold text-retro-orange">
                                                            {{ $sugar->prices->first()?->price ?? 0 }} DH
                                                        </p>
                                                    </div>

                                                    @if ($this->selectedSugar === $sugar->id)
                                                        <span class="absolute top-4 right-4 text-4xl">✓</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Step 4: Add-ons -->
                            @if ($this->configuration?->has_addons)
                                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-x-10"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-x-0"
                                    x-transition:leave-end="opacity-0 transform -translate-x-10">
                                    <h3 class="text-4xl font-bold mb-8 text-retro-blue text-center">
                                        {{ __('Select Add-ons') }} {!! $this->configuration?->getIcon('addons') !!}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                                        @foreach ($this->availableAddons as $addon)
                                            <div class="transform transition-all duration-300 hover:scale-105">
                                                <div class="relative bg-white p-6 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange
                                                {{ in_array($addon->id, $this->selectedAddons) ? 'ring-8 ring-retro-orange' : '' }}"
                                                    wire:click="toggleAddon({{ $addon->id }})">

                                                    <div class="text-center">
                                                        <span
                                                            class="text-8xl mb-6 block">{!! $this->configuration?->getIcon('addons') !!}</span>
                                                        <h4 class="text-2xl font-bold text-retro-blue">
                                                            {{ $addon->name }}
                                                        </h4>
                                                        <div class="mt-2 text-sm text-gray-600">
                                                            <p class="font-semibold mt-1">+{{ $addon->price }} DH</p>
                                                        </div>
                                                    </div>

                                                    @if (in_array($addon->id, $this->selectedAddons))
                                                        <span class="absolute top-4 right-4 text-4xl">✓</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Step 5: Size Selection -->
                            @if ($this->configuration?->has_size)
                                <div x-show="step === 5" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-x-10"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-x-0"
                                    x-transition:leave-end="opacity-0 transform -translate-x-10">
                                    <h3 class="text-4xl font-bold mb-8 text-retro-blue text-center">
                                        {{ __('Select Size') }} {!! $this->configuration?->getIcon('size') !!}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                                        @foreach ($this->availableSizes as $key => $size)
                                            <div class="transform transition-all duration-300 hover:scale-105">
                                                <div class="relative bg-white p-6 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange
                                                {{ $this->selectedSize === $key ? 'ring-8 ring-retro-orange' : '' }}"
                                                    wire:click="selectSize('{{ $key }}')">

                                                    <div class="text-center">
                                                        <span
                                                            class="text-8xl mb-6 block">{!! $this->configuration?->getIcon('size') !!}</span>
                                                        <h4 class="text-2xl font-bold text-retro-blue">
                                                            {{ $size['name'] }}
                                                        </h4>
                                                        <p class="text-lg text-retro-blue mt-2">
                                                            {{ $size['capacity'] }}
                                                        </p>
                                                    </div>

                                                    @if ($this->selectedSize === $key)
                                                        <span class="absolute top-4 right-4 text-4xl">✓</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between my-4 md:my-6">
                            <button wire:click="previousStep"
                                class="flex items-center gap-2 md:gap-4 bg-retro-blue text-white px-4 md:px-8 py-2 md:py-4 text-sm md:text-xl rounded-full hover:bg-retro-cream hover:text-retro-blue border-4 border-retro-blue transition-all duration-300 transform hover:scale-105"
                                :disabled="step === 1" x-show="step > 1">
                                <span class="text-xl md:text-3xl">👈</span>
                                <span class="font-bold">{{ __('Previous') }}</span>
                            </button>

                            <button wire:click="nextStep"
                                class="flex items-center gap-2 md:gap-4 bg-retro-orange text-white px-4 md:px-8 py-2 md:py-4 text-sm md:text-xl rounded-full hover:bg-retro-yellow hover:text-retro-blue border-4 border-retro-orange transition-all duration-300 transform hover:scale-105"
                                x-show="step < {{ count($this->steps) }}">
                                <span class="font-bold">{{ __('Next') }}</span>
                                <span class="text-xl md:text-3xl">👉</span>
                            </button>

                            <button wire:click="addToCart"
                                class="flex items-center gap-2 md:gap-4 bg-retro-green text-white px-4 md:px-8 py-2 md:py-4 text-sm md:text-xl rounded-full hover:bg-retro-yellow hover:text-retro-green border-4 border-retro-green transition-all duration-300 transform hover:scale-105"
                                x-show="step === {{ count($this->steps) }}" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="addToCart">
                                    <span class="text-xl md:text-3xl">🛒</span>
                                    <span class="font-bold">{{ __('Add to Cart') }}</span>
                                </span>
                                <span wire:loading wire:target="addToCart">
                                    <span class="text-xl md:text-3xl">⏳</span>
                                    <span class="font-bold">{{ __('Adding...') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar: Cart -->
                    <div class="lg:w-1/4 order-2 lg:order-1">
                        <!-- Price Display Section -->
                        <div class="border-retro-orange border-solid border-4 bg-retro-yellow rounded-lg shadow-lg p-4 md:p-6 mb-4 md:mb-6 text-center">
                            <h3 class="text-xl md:text-2xl text-retro-blue font-bold mb-2">{{ __('Current Price') }}</h3>
                             <div wire:loading wire:target="updateTotalPrice" class="text-retro-blue text-lg md:text-xl">{{ __('Calculating...') }} ⏳</div>
                             <div wire:loading.remove wire:target="updateTotalPrice">
                                @if($error)
                                   <p class="text-red-600 font-semibold text-sm md:text-base">{{ $error }}</p>
                                @else
                                    <p class="text-3xl md:text-4xl text-retro-blue font-bold">{{ number_format($totalPrice, 2) }} DH</p>
                                @endif
                             </div>
                        </div>

                        <div
                            class="border-retro-orange border-solid border-4 bg-white rounded-lg shadow-lg px-4 md:px-6">
                            <h3 class="text-xl md:text-2xl text-retro-orange font-bold">
                                {{ __('Your ' . $this->selectedCategory?->name) }} {!! $this->configuration?->getIcon('product') !!}
                            </h3>
                            <div class="flex flex-col gap-y-2 md:gap-y-4 rounded-lg mb-4 md:mb-6 text-retro-blue">
                                @if (count($this->selectedIngredients) > 0)
                                    <div>
                                        <span class="font-medium">{{ __('Ingredients') }}:</span>
                                        <ol class="text-xs md:text-sm mb-1">
                                            @foreach ($this->availableIngredients->whereIn('id', $this->selectedIngredients)->pluck('name') as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @endif

                                @if ($this->selectedBase && $this->configuration?->has_base)
                                    <div>
                                        <span class="font-medium">{{ __('Base') }}:</span>
                                        <p class="text-xs md:text-sm mb-1">
                                            {{ $this->availableBases->where('id', $this->selectedBase)->first()?->name }}
                                        </p>
                                    </div>
                                @endif

                                @if ($this->selectedSugar && $this->configuration?->has_sugar)
                                    <div>
                                        <span class="font-medium">{{ __('Sugar') }}:</span>
                                        <p class="text-xs md:text-sm mb-1">
                                            {{ $this->availableSugars->where('id', $this->selectedSugar)->first()?->name }}
                                        </p>
                                    </div>
                                @endif

                                @if (count($this->selectedAddons) > 0 && $this->configuration?->has_addons)
                                    <div>
                                        <span class="font-medium">{{ __('Add-ons') }}:</span>
                                        <ul class="text-xs md:text-sm mb-1">
                                            @foreach ($this->availableAddons->whereIn('id', $this->selectedAddons)->pluck('name') as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($this->selectedSize && $this->configuration?->has_size)
                                    <div>
                                        <span class="font-medium">{{ __('Size') }}:</span>
                                        <p class="capitalize text-xs md:text-sm mb-1">
                                            {{ $this->availableSizes[$this->selectedSize]['name'] }} -
                                            {{ $this->availableSizes[$this->selectedSize]['capacity'] }}
                                        </p>
                                    </div>
                                @endif

                                @if (count($this->selectedIngredients) === 0)
                                    <p class="text-retro-blue text-xs md:text-sm underline mt-4">
                                        {{ __("Start composing your {$this->selectedCategory->name}!") }}
                                        {!! $this->configuration?->getIcon('product') !!}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Cart Section -->
                        <div
                            class="border-retro-orange border-solid border-4 bg-white rounded-lg shadow-lg mt-4 px-4 md:px-6">
                            <h3 class="text-xl md:text-2xl text-retro-orange font-bold">{{ __('Cart') }} 🛒</h3>
                            <div class="">
                                @forelse ($this->cart as $index => $item)
                                    <div class="border-b border-retro-orange/20 py-2 md:py-4">
                                        <h5 class="font-medium text-retro-blue text-center text-base md:text-lg mb-2">
                                            {{ $item['category'] }}</h5>
                                        <div class="flex flex-col items-center gap-2 md:gap-4">
                                            <div
                                                class="flex items-center bg-white rounded-lg shadow-sm border border-retro-orange/20">
                                                <x-button color="infoOutline" type="button"
                                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})">
                                                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </x-button>

                                                <input type="number"
                                                    wire:model.live="cart.{{ $index }}.quantity"
                                                    wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                    class="w-12 md:w-16 h-auto text-center border-0 hover:bg-transparent active:bg-transparent focus:ring-0 p-1 md:p-2"
                                                    min="1" value="{{ $item['quantity'] }}">

                                                <x-button color="infoOutline" type="button"
                                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})">
                                                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </x-button>
                                            </div>

                                            <div class="flex gap-2">
                                                <x-button color="dangerOutline" type="button"
                                                    wire:click="removeFromCart({{ $index }})">
                                                    ❌
                                                </x-button>
                                            </div>
                                        </div>

                                        <p class="text-center text-retro-blue font-semibold mt-2">
                                            {{ $item['price'] * $item['quantity'] }} DH
                                        </p>
                                    </div>
                                @empty
                                    <p
                                        class="text-retro-blue text-center text-xs md:text-sm py-4 bg-retro-cream/20 rounded-lg">
                                        {{ __('Your cart is empty.') }} 🛒
                                    </p>
                                @endforelse
                            </div>

                            <div
                                class="flex justify-between items-center text-retro-blue font-bold text-base md:text-lg my-4 md:my-6">
                                <span>{{ __('Total') }}: 💰</span>
                                <span>{{ $this->cartTotal }} DH</span>
                            </div>

                            @if (count($this->cart) > 0)
                                <button type="button" wire:click="placeOrder"
                                    class="w-full mb-4 bg-retro-orange text-white py-2 px-4 rounded-full hover:bg-retro-yellow hover:text-retro-orange transition duration-300">
                                    <span wire:loading.remove wire:target="placeOrder">
                                        {{ __('Place Order') }} ✅
                                    </span>
                                    <span wire:loading wire:target="placeOrder">
                                        {{ __('Processing...') }} ⏳
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layered Juice Section -->
        @if ($selectedCategory && $selectedCategory->slug === 'layered-juice')
            <div class="border-retro-orange border-solid border-4 bg-white rounded-lg shadow-lg p-6 mb-4">
                <h3 class="text-2xl text-retro-orange font-bold mb-4">
                    {{ __('Design Your Layered Juice') }} 🥤
                </h3>

                <!-- Layer Builder -->
                <div class="space-y-6">
                    <!-- Current Layers -->
                    @if (count($selectedLayers) > 0)
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-retro-blue">{{ __('Your Layers') }}</h4>
                            @foreach ($selectedLayers as $index => $layer)
                                <div class="flex items-center justify-between bg-retro-cream p-4 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-retro-orange font-bold">{{ $index + 1 }}</span>
                                        <span class="text-retro-blue">
                                            {{ $this->availableIngredients->firstWhere('id', $layer['ingredient_id'])?->name }}
                                        </span>
                                        <span class="text-gray-600 text-sm">({{ $layer['type'] }})</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($index > 0)
                                            <button wire:click="moveLayer({{ $index }}, {{ $index - 1 }})"
                                                class="text-retro-orange hover:text-retro-orange-dark">
                                                ↑
                                            </button>
                                        @endif
                                        @if ($index < count($selectedLayers) - 1)
                                            <button wire:click="moveLayer({{ $index }}, {{ $index + 1 }})"
                                                class="text-retro-orange hover:text-retro-orange-dark">
                                                ↓
                                            </button>
                                        @endif
                                        <button wire:click="removeLayer({{ $index }})"
                                            class="text-red-500 hover:text-red-700">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Layer Type Selector -->
                    @if (count($selectedLayers) < config('composable.juice.layers.max_layers'))
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-retro-blue">
                                {{ __('Add Layer') }} #{{ $currentLayer }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($this->availableLayerTypes as $type => $config)
                                    <button wire:click="$set('layerTypes', '{{ $type }}')"
                                        class="p-4 rounded-lg border-2 {{ $layerTypes === $type ? 'border-retro-orange bg-retro-cream' : 'border-gray-200' }}">
                                        <h5 class="font-medium text-retro-blue">{{ $config['name'] }}</h5>
                                        <p class="text-sm text-gray-600">
                                            {{ __('Max') }}: {{ $config['max_per_drink'] }}
                                        </p>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Ingredient Selector -->
                    @if ($layerTypes)
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-retro-blue">
                                {{ __('Select Ingredient') }}
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($this->availableIngredients->where('type', $layerTypes) as $ingredient)
                                    <button wire:click="addLayer('{{ $ingredient->id }}', '{{ $layerTypes }}')"
                                        class="p-4 rounded-lg border-2 border-gray-200 hover:border-retro-orange hover:bg-retro-cream transition-colors">
                                        <h5 class="font-medium text-retro-blue">{{ $ingredient->name }}</h5>
                                        @if ($ingredient->prices->isNotEmpty())
                                            <p class="text-sm text-gray-600">
                                                {{ $ingredient->prices->first()->price }} MAD
                                            </p>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Suggested Combinations -->
                    @if (count($selectedLayers) > 0)
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-retro-blue">
                                {{ __('Popular Combinations') }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($this->suggestedCombinations['popular_combinations'] as $combo)
                                    <div class="p-4 rounded-lg border-2 border-gray-200">
                                        <h5 class="font-medium text-retro-blue">
                                            {{ implode(', ', $combo['ingredients']) }}
                                        </h5>
                                        <p class="text-sm text-gray-600">
                                            {{ __('Ordered') }}: {{ $combo['frequency'] }} {{ __('times') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>
