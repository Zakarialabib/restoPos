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
        }"
            x-init="initSwiper();
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
            });" x-bind:class="getTheme('bg')" class="min-h-screen w-full flex flex-col">

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

                <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                    @foreach ($this->products as $product)
                        <div class="text-center px-2 cursor-pointer hover:bg-orange-100 transition duration-300"
                            x-bind:class="getTheme('card').bg">
                            <div class="md:flex">
                                <div class="md:shrink-0">
                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                        onerror="this.onerror=null;" class="h-48 w-full object-cover md:h-full md:w-48">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold" x-bind:class="getTheme('card').product_name">
                                        {{ $product->name }}</h3>
                                    {{-- <p class="text-sm" x-bind:class="getTheme('card').product_description">
                                        {{ Str::limit($product->description, 50) }}</p> --}}
                                    <span class="text-lg font-bold mt-2 text-center"
                                        x-bind:class="getTheme('card').button">
                                        {{ $product->price }}</span>
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
                                <div class="grid grid-cols-3 gap-x-6 gap-y-2 px-4 my-4 py-4 items-center">
                                    @foreach ($chunk as $product)
                                        <div class="text-center px-4" x-bind:class="getTheme('card').bg">
                                            <div class="md:flex">
                                                @if ($product->image)
                                                    <div class="md:shrink-0">
                                                        <img src="{{ asset('images/' . $product->image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-48 w-full object-cover md:h-full md:w-48">
                                                    </div>
                                                @endif
                                                <div class="p-4">
                                                    <h3 class="text-2xl font-bold mb-2"
                                                        x-bind:class="getTheme('card').product_name">
                                                        {{ $product->name }}</h3>
                                                    {{-- <p class="text-lg mb-4"
                                                        x-bind:class="getTheme('card').product_description">
                                                        {{ Str::limit($product->description, 100) }}</p> --}}
                                                    <p class="text-lg font-bold"
                                                        x-bind:class="getTheme('card').button">
                                                        {{ $product->price }}</p>
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
