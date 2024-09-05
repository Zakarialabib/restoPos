<?php

use function Livewire\Volt\{layout, state, computed, title};
use function Livewire\Volt\{with};
use App\Models\Product;
use App\Models\Category;

layout('layouts.guest');
title('Menu');

with(
    fn() => [
        'products' => Product::query()
            ->where('is_available', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->get(),
        'categories' => Category::query()->get(),
    ],
);

state([
    'currentTheme' => 'dark',
    'themes' => [
        'earth' => [
            'bg' => 'bg-[#FFFBEB]',
            'text' => 'text-[#292524]',
            'accent' => 'text-[#059669]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg',
                'button' => 'bg-[#E07A5F] hover:bg-[#C65D45] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#292524]',
                'product_price' => 'text-4xl font-semibold text-[#E07A5F]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4A6670]',
            ],
            'header' => [
                'bg' => 'bg-[#6B9080]',
                'text' => 'text-white',
            ],
        ],
        'pastel' => [
            'bg' => 'bg-[#FFF1F2]',
            'text' => 'text-[#334155]',
            'accent' => 'text-[#38BDF8]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#C4B5FD] hover:bg-[#A78BFA] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#334155]',
                'product_price' => 'text-4xl font-semibold text-[#C4B5FD]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#34D399]',
            ],
            'header' => [
                'bg' => 'bg-[#86EFAC]',
                'text' => 'text-white',
            ],
        ],
        'vibrant' => [
            'bg' => 'bg-[#FACC15]',
            'text' => 'text-[#581C87]',
            'accent' => 'text-[#EC4899]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#EC4899] hover:bg-[#DB2777] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#581C87]',
                'product_price' => 'text-4xl font-semibold text-[#EC4899]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#7E22CE]',
            ],
            'header' => [
                'bg' => 'bg-[#7C3AED]',
                'text' => 'text-white',
            ],
        ],
        'monochrome' => [
            'bg' => 'bg-[#F3F4F6]',
            'text' => 'text-[#111827]',
            'accent' => 'text-[#4B5563]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#1F2937] hover:bg-[#111827] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#111827]',
                'product_price' => 'text-4xl font-semibold text-[#1F2937]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4B5563]',
            ],
            'header' => [
                'bg' => 'bg-[#374151]',
                'text' => 'text-white',
            ],
        ],
        'dark' => [
            'bg' => 'bg-[#111827]',
            'text' => 'text-[#F3F4F6]',
            'accent' => 'text-[#34D399]',
            'card' => [
                'bg' => 'bg-[#1F2937]',
                'button' => 'bg-[#10B981] hover:bg-[#059669] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#34D399]',
                'product_price' => 'text-4xl font-semibold text-[#10B981]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#D1D5DB]',
            ],
            'header' => [
                'bg' => 'bg-[#1F2937]',
                'text' => 'text-[#34D399]',
            ],
        ],
        'ocean' => [
            'bg' => 'bg-[#E0F2FE]',
            'text' => 'text-[#0C4A6E]',
            'accent' => 'text-[#0891B2]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#0EA5E9] hover:bg-[#0284C7] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#0C4A6E]',
                'product_price' => 'text-4xl font-semibold text-[#0EA5E9]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#0369A1]',
            ],
            'header' => [
                'bg' => 'bg-[#0284C7]',
                'text' => 'text-white',
            ],
        ],
        'forest' => [
            'bg' => 'bg-[#ECFDF5]',
            'text' => 'text-[#064E3B]',
            'accent' => 'text-[#059669]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#10B981] hover:bg-[#059669] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#064E3B]',
                'product_price' => 'text-4xl font-semibold text-[#10B981]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#047857]',
            ],
            'header' => [
                'bg' => 'bg-[#059669]',
                'text' => 'text-white',
            ],
        ],
        'sunset' => [
            'bg' => 'bg-[#FFEDD5]',
            'text' => 'text-[#7C2D12]',
            'accent' => 'text-[#EA580C]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#EA580C] hover:bg-[#C2410C] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#7C2D12]',
                'product_price' => 'text-4xl font-semibold text-[#EA580C]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#9A3412]',
            ],
            'header' => [
                'bg' => 'bg-[#C2410C]',
                'text' => 'text-white',
            ],
        ],
        'neon' => [
            'bg' => 'bg-[#0F172A]',
            'text' => 'text-[#F472B6]',
            'accent' => 'text-[#3B82F6]',
            'card' => [
                'bg' => 'bg-[#1E293B]',
                'button' => 'bg-[#3B82F6] hover:bg-[#2563EB] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#F472B6]',
                'product_price' => 'text-4xl font-semibold text-[#3B82F6]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#818CF8]',
            ],
            'header' => [
                'bg' => 'bg-[#2563EB]',
                'text' => 'text-white',
            ],
        ],
        'elegant' => [
            'bg' => 'bg-[#F0EAE2]',
            'text' => 'text-[#3D3D3D]',
            'accent' => 'text-[#8E7F7F]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg',
                'button' => 'bg-[#8E7F7F] hover:bg-[#6D5D5D] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#3D3D3D]',
                'product_price' => 'text-4xl font-semibold text-[#8E7F7F]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#5A5A5A]',
            ],
            'header' => [
                'bg' => 'bg-[#8E7F7F]',
                'text' => 'text-white',
            ],
        ],
        'rustic' => [
            'bg' => 'bg-[#F5E6D3]',
            'text' => 'text-[#4A3933]',
            'accent' => 'text-[#A67C52]',
            'card' => [
                'bg' => 'bg-[#FFFFFF] bg-opacity-70',
                'button' => 'bg-[#A67C52] hover:bg-[#8C6642] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#4A3933]',
                'product_price' => 'text-4xl font-semibold text-[#A67C52]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#6B5B54]',
            ],
            'header' => [
                'bg' => 'bg-[#A67C52]',
                'text' => 'text-white',
            ],
        ],
        'fresh' => [
            'bg' => 'bg-[#E8F3E8]',
            'text' => 'text-[#2C5F2D]',
            'accent' => 'text-[#97BC62]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80',
                'button' => 'bg-[#97BC62] hover:bg-[#7FA650] text-white',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#2C5F2D]',
                'product_price' => 'text-4xl font-semibold text-[#97BC62]',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4A7B4B]',
            ],
            'header' => [
                'bg' => 'bg-[#97BC62]',
                'text' => 'text-white',
            ],
        ],
    ],
    'isOpen' => false,
    'search' => '',
    'activeCategory' => 'all',
    'category_id' => '',
]);

$getTheme = function ($key) {
    return $this->themes[$this->currentTheme][$key];
};

$changeTheme = function ($theme) {
    $this->currentTheme = $theme;
};

$togglePanel = function () {
    $this->isOpen = !$this->isOpen;
};

?>
<div>
    <div x-data="{
        currentTheme: @entangle('currentTheme'),
        isOpen: @entangle('isOpen'),
        themes: @entangle('themes'),
        activeCategory: @entangle('activeCategory'),
        getTheme(key) {
            return this.themes[this.currentTheme][key];
        },
        isMobile: window.innerWidth < 768,
        swiper: null,
        initSwiper() {
            if (!this.isMobile && !this.swiper) {
                this.swiper = new Swiper('.swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
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

        <header x-show="isMobile" x-bind:class="[getTheme('header').bg, getTheme('header').text]" class="py-2">
            <div class="mx-auto px-4 flex justify-between items-center">
                <h1 class="text-3xl font-bold">Menu</h1>
                <button @click="$wire.togglePanel()" x-bind:class="getTheme('card').button" class="px-4 py-2 rounded">
                    <span class="material-icons">settings</span>
                </button>
            </div>
        </header>

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
                    @foreach ($categories as $category)
                        <button wire:click="$set('category_id', '{{ $category->id }}')"
                            class="px-4 py-2 rounded whitespace-nowrap" x-bind:class="getTheme('button')">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach ($products as $product)
                    <div class="product-card cursor-pointer" x-bind:class="getTheme('card').bg">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                            class="w-full h-32 object-cover rounded-t">
                        <div class="p-2">
                            <h3 class="text-lg font-semibold" x-bind:class="getTheme('card').product_name">
                                {{ $product->name }}</h3>
                            <p class="text-sm" x-bind:class="getTheme('card').product_description">
                                {{ Str::limit($product->description, 50) }}</p>
                            <p class="text-lg font-bold mt-2 text-center" x-bind:class="getTheme('card').button">
                                {{ number_format($product->price, 2) }} DH</p>
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
            <div class="swiper-container h-full">
                <div class="swiper-wrapper">
                    @foreach ($products->chunk(6) as $chunk)
                        <div class="swiper-slide">
                            <div class="grid grid-cols-3 gap-6 p-8">
                                @foreach ($chunk as $product)
                                    <div class="product-card" x-bind:class="getTheme('card').bg">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover rounded-t">
                                        <div class="p-4">
                                            <h3 class="text-2xl font-bold mb-2"
                                                x-bind:class="getTheme('card').product_name">{{ $product->name }}</h3>
                                            <p class="text-lg mb-4"
                                                x-bind:class="getTheme('card').product_description">
                                                {{ Str::limit($product->description, 100) }}</p>
                                            <p class="text-3xl font-bold" x-bind:class="getTheme('card').button">
                                                {{ number_format($product->price, 2) }} DH</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Theme Panel -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full" x-bind:class="getTheme('card').bg"
            class="fixed inset-y-0 right-0 w-72 shadow-lg p-6 overflow-y-auto" x-on:click.away="$wire.isOpen = false"
            x-on:keydown.escape="$wire.isOpen = false" x-cloak>
            <h2 x-bind:class="getTheme('text')" class="text-2xl font-bold mb-4">Theme Options</h2>
            <div class="mb-8 grid grid-cols-2 gap-4">
                @foreach (['earth', 'pastel', 'vibrant', 'monochrome', 'dark', 'ocean', 'forest', 'sunset', 'neon', 'elegant', 'rustic', 'fresh'] as $theme)
                    <button wire:click="$set('currentTheme', '{{ $theme }}')"
                        x-bind:class="[getTheme('card').button, currentTheme === '{{ $theme }}' ?
                            'ring-2 ring-offset-2 ring-opacity-60' : ''
                        ]"
                        class="px-4 py-2 rounded-full text-sm">
                        {{ ucfirst($theme) }}
                    </button>
                @endforeach
            </div>
            <button @click="$wire.togglePanel()" x-bind:class="getTheme('card').button"
                class="w-full mt-4 px-4 py-2 rounded-full">
                x
            </button>
        </div>
    </div>
</div>
