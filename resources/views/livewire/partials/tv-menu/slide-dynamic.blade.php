@props(['product', 'config'])

{{-- Dynamic Card - Adjust classes based on product data e.g., featured --}}
@php
    // Example: Make featured items potentially larger (requires Swiper slidesPerView: 'auto')
    $cardClasses = $product->is_featured
        ? 'h-64 sm:h-80' // Taller featured card
        : 'h-56 sm:h-64'; // Standard height
    $imageHeight = $product->is_featured ? 'h-40 sm:h-48' : 'h-32 sm:h-40';
    $titleSize = $product->is_featured ? 'text-xl sm:text-2xl' : 'text-lg sm:text-xl';
    $priceSize = $product->is_featured ? 'text-lg sm:text-xl' : 'text-base sm:text-lg';
@endphp

<div class="product-card-base flex flex-col {{ $cardClasses }} rounded-lg overflow-hidden shadow-lg transition-transform duration-300 ease-in-out hover:scale-[1.03]"
    style="background-color: var(--background-color-alpha); border: 1px solid var(--text-color-alpha);">

    {{-- Image Area --}}
    @if ($product->image)
        <div class="relative {{ $imageHeight }} flex-shrink-0">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover" loading="lazy">
            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/50 to-transparent"></div>
            @if ($product->is_featured)
                <div class="absolute top-2 right-2 bg-accent text-white px-2 py-0.5 rounded-full text-xs font-semibold shadow animate-pulse"
                    style="background-color: var(--accent-color); color: var(--background-color);">
                    Featured
                </div>
            @endif
        </div>
    @else
        <div
            class="relative {{ $imageHeight }} flex-shrink-0 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            {{-- Placeholder Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif

    {{-- Content Area --}}
    <div class="p-3 sm:p-4 flex flex-col flex-grow">
        <h3 class="{{ $titleSize }} font-semibold mb-1 line-clamp-2 flex-grow" style="color: var(--text-color);">
            {{ $product->name }}
        </h3>

        @if ($config['showDescription'] && $product->description)
            <p class="text-xs sm:text-sm opacity-70 mt-1 line-clamp-2" style="color: var(--text-color);">
                {{-- Slightly more description space --}}
                {{ $product->description }}
            </p>
        @endif

        @if ($config['showPrices'])
            <div class="mt-auto pt-2 {{ $priceSize }} font-bold" style="color: var(--accent-color);">
                {{-- Ensure price is at bottom --}}
                {{ number_format($product->price, 2) }} DH
            </div>
        @endif
    </div>
</div>
