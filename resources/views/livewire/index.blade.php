<?php

use function Livewire\Volt\{layout, mount, state, title};
use App\Models\Product;

layout('layouts.guest');
title('Menu');
state(['products' => fn() => Product::where('is_available', true)->get()]);
state(['theme']);

mount(function () {
    $this->theme = [
        'bg' => 'bg-white',
        'text' => 'text-gray-800',
        'accent' => 'text-red-400',
        'card' => 'bg-gray-200 bg-opacity-50 backdrop-filter backdrop-blur-lg',
        'button' => 'bg-red-500 hover:bg-red-600 text-gray-900',
        'header' => 'bg-gray-100 text-gray-900',
        'category' => 'bg-gray-200 text-gray-800 hover:bg-gray-300',
        'product_name' => 'text-lg lg:text-xl font-semibold',
        'product_price' => 'text-4xl font-semibold',
        'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed',
    ];
});

?>
<div>
    <div x-data="{
        currentPage: 0,
        productsPerPage: 3,
        totalPages: Math.ceil({{ $products->count() }} / 3),
        startAutoplay() {
            this.autoplay = setInterval(() => {
                this.nextSlide();
            }, 5000);
        },
        nextSlide() {
            this.currentPage = (this.currentPage + 1) % this.totalPages;
        }
    }" x-init="startAutoplay()"
        class="h-screen flex flex-col {{ $theme['bg'] }} {{ $theme['text'] }}">

        <!-- Hero Section -->
        <header class="text-center {{ $theme['header'] }}">
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-normal my-2 text-shadow">
                <span class="{{ $theme['accent'] }}">{{ __('Menu') }}</span>
            </h1>

            <div class="w-full justify-center flex flex-row flex-wrap gap-6">
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">restaurant_menu</i></span>ALL
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">local_bar</i></span>COLD DRINK
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">local_pizza</i></span>PIZZA
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">restaurant</i></span>SALAD
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">cake</i></span>SWEETS
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden cursor-pointer rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">whatshot</i></span>SPICY
                </p>
                <p
                    class="flex items-center lg:text-[20px] text-[18px] overflow-hidden rounded-lg p-3 shadow-md transition-transform transform hover:scale-105 {{ $theme['category'] }}">
                    <span class="mb-0 mr-2"><i class="material-icons text-[30px]">fastfood</i></span>BURGER
                </p>
            </div>
        </header>

        <!-- Menu Items -->
        <div class="flex-grow relative overflow-hidden">
            <div class="absolute inset-0 flex transition-transform duration-1000 ease-in-out"
                :style="{ transform: `translateX(-${currentPage * 100}%)` }">
                @foreach ($products->chunk(3) as $chunk)
                    <div class="w-full flex-shrink-0 flex justify-center items-center gap-x-4">
                        @foreach ($chunk as $product)
                            <div
                                class="{{ $theme['card'] }} rounded-lg w-1/3 h-[calc(100vh-12rem)] flex flex-col items-center justify-between shadow-lg transition-transform transform hover:scale-105">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-1/2 object-cover rounded-t-lg">
                                <div class="w-full flex flex-col justify-between p-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <p class="{{ $theme['product_name'] }}">
                                            <a href="#"
                                                class="{{ $theme['text'] }} hover:{{ $theme['accent'] }} transition duration-300 max-w-[280px] text-ellipsis overflow-hidden block whitespace-nowrap">
                                                {{ $product->name }}
                                            </a>
                                        </p>
                                    </div>
                                    <span
                                        class="absolute top-0 {{ $theme['button'] }} left-0 text-white rounded-ee-[10px] uppercase 
                                            py-3 px-5 font-semibold text-4xl z-[2]">
                                        {{ number_format($product->price, 2) }} DH
                                    </span>
                                    <p class="{{ $theme['product_description'] }}">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
