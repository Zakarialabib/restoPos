<div>
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div x-data="{
        currentTheme: @entangle('currentTheme'),
        themes: @entangle('themes'),
        activeCategory: @entangle('activeCategory'),
        getTheme(key) {
            return this.themes[this.currentTheme][key];
        }">
        <div x-data="{
            isMobile: window.innerWidth < 768,
            swiper: null,
            initSwiper() {
                if (!this.isMobile && !this.swiper) {
                    this.swiper = new Swiper('.swiper-container', {
                        loop: true,
                        autoplay: {
                            delay: 10000,
                        },
                        effect: 'fade',
                        fadeEffect: {
                            crossFade: true
                        },
                        slidesPerView: 1,
                    });
                }
            }
        }" x-init="initSwiper();
        $watch('isMobile', value => {
            if (!value) {
                $nextTick(() => initSwiper());
            } else if (swiper) {
                swiper.destroy();
                swiper = null;
            }
        });
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth < 768;
        });" x-bind:class="getTheme('bg')"
            class="min-h-screen w-full flex flex-col">

            <!-- Mobile View -->
            <div x-show="isMobile" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" class="flex-grow p-4 relative">

                <div
                    class="px-2 py-4 mb-4 flex flex-col border rounded bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg">
                    <input wire:model.live="search" type="text" placeholder="Search menu..."
                        class="w-full py-2 border rounded mb-4 placeholder:text-gray-500 border-gray-300"
                        x-bind:class="getTheme('input')">

                    <div class="flex space-x-2 overflow-x-auto">
                        <button wire:click="$set('activeCategory', 'all')"
                            class="bg-blue-500 text-white px-4 py-2 rounded whitespace-nowrap"
                            x-bind:class="getTheme('button')">
                            All
                        </button>
                        @foreach ($this->categories as $category)
                            <button wire:click="$set('category_id', '{{ $category->id }}')"
                                class="px-4 py-2 rounded whitespace-nowrap" x-bind:class="getTheme('button')">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-2xl font-semibold">{{ __('Compose') }}</h1>
                        <p class="text-sm text-gray-500">{{ __('Compose your order') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('compose.juices') }}" class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-2">{{ __('Juices') }}</h2>
                    </a>
                    <a href="{{ route('compose.salade') }}" class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-2">{{ __('Salade') }}</h2>
                    </a>
                    <a href="{{ route('compose.dried-fruits') }}"
                        class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-2">{{ __('Dried Fruits') }}</h2>
                    </a>
                    <a href="#" class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-xl font-bold mb-2">{{ __('Tea') }}</h2>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                    @foreach ($this->products as $product)
                        <div class="text-center px-2 cursor-pointer hover:bg-orange-100 transition duration-300"
                            x-bind:class="getTheme('card').bg">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold" x-bind:class="getTheme('card').product_name">
                                    {{ $product->name }}</h3>
                                <div class="mt-2">
                                    @foreach ($product->prices as $price)
                                        <p class="text-sm">
                                            {{ $price->getSize() }}:
                                            <span class="font-bold">{{ $price->price }}</span>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TV View -->
            <div x-show="!isMobile" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" class="flex-grow">
                <div class="swiper-container h-full flex items-center align-middle">
                    <div class="swiper-wrapper">
                        @foreach ($this->products->chunk(6) as $chunk)
                            <div class="swiper-slide">
                                <div class="grid grid-cols-3 gap-x-6 gap-y-4 px-4 my-4 py-4 items-center">
                                    @foreach ($chunk as $product)
                                        <div class="text-center transition-transform transform hover:scale-105 shadow-lg border-2 border-retro-orange rounded-lg overflow-hidden bg-white hover:bg-orange-50 duration-300"
                                            x-bind:class="getTheme('card').bg">
                                            <div class="md:flex justify-between">
                                                @if ($product->image)
                                                    <div class="md:shrink-0">
                                                        <img src="{{ asset('images/' . $product->image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-48 w-full object-cover md:h-full md:w-48">
                                                    </div>
                                                @endif
                                                <div
                                                    class="p-4 flex flex-col justify-between h-full border-solid border-1 border-retro-yellow">
                                                    <h3 class="text-2xl font-bold mb-2"
                                                        x-bind:class="getTheme('card').product_name">
                                                        {{ $product->name }}</h3>
                                                    <div class="mt-auto border-t border-gray-300 pt-2">
                                                        @if ($product->price)
                                                            <div class="text-lg font-semibold"
                                                                x-bind:class="getTheme('card').product_price">
                                                                {{ $product->price }}
                                                            </div>
                                                        @else
                                                            <div class="mt-2">
                                                                @foreach ($product->prices as $price)
                                                                    <div
                                                                        class="flex items-center gap-x-6 border-2 border-gray-300 border-solid p-4 rounded-lg justify-between mb-2 hover:bg-orange-50 transition-colors duration-200 shadow-md">
                                                                        <span
                                                                            class="text-2xl font-bold text-retro-orange"
                                                                            x-bind:class="getTheme('card').product_price"
                                                                            style="font-size: {{ strlen($price->price) > 5 ? '2.5rem' : '2rem' }}">
                                                                            {{ $price->price }}
                                                                        </span>
                                                                        <span
                                                                            class="material-symbols-outlined
                                                                                text-gray-500"
                                                                            style="font-size: {{ strlen($price->getSize()) > 3 ? '28px' : '20px' }}">
                                                                            glass_cup
                                                                        </span>
                                                                        <span class="text-xs text-gray-500 gap-x-2"
                                                                            style="font-size: {{ strlen($price->getSize()) > 3 ? '1.1rem' : '0.9rem' }}">
                                                                            {{ __($price->getSize()) }} -
                                                                            {{ __('Size') }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm lg:text-base opacity-75 leading-relaxed"
                                                        x-bind:class="getTheme('card').product_description">
                                                        {{ $product->description ?? 'No description available.' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
