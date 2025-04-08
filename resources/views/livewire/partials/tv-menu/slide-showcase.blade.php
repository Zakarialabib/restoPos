@props(['product', 'config'])

{{-- Base container for the slide content --}}
<div class="product-card-base relative h-full flex flex-col justify-end text-white overflow-hidden"> {{-- Adjust height/flex as needed --}}

    {{-- Background Image (Dominant & Blurred/Ken Burns) --}}
    @if ($product->image)
        <div class="absolute inset-0 z-0">
            {{-- Option 1: Simple Image --}}
            {{-- <img src="{{ $product->image }}" alt="" class="w-full h-full object-cover"> --}}

            {{-- Option 2: Ken Burns Effect (Requires CSS class 'ken-burns-image' defined in main CSS) --}}
            <img src="{{ $product->image }}" alt="" class="ken-burns-image w-full h-full object-cover">

             {{-- Optional: Blurred backdrop using the same image --}}
            {{-- <div class="absolute inset-0 bg-cover bg-center backdrop-filter backdrop-blur-lg" style="background-image: url('{{ $product->image }}');"></div> --}}
        </div>
        {{-- Dark overlay for text contrast --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-transparent z-10"></div>
    @else
        {{-- Fallback background if no image --}}
        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900 z-0"></div>
    @endif

    {{-- Content Area (Positioned above the overlay) --}}
    <div class="relative z-20 p-8 md:p-12 lg:p-16 space-y-4 md:space-y-6">

        {{-- Featured Badge --}}
        @if ($product->is_featured)
            <div class="absolute top-4 right-4 bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold shadow animate-pulse" style="background-color: var(--accent-color); color: var(--background-color);">
                Featured
            </div>
        @endif

        {{-- Title --}}
        <h3 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight shadow-black text-shadow-lg" style="color: var(--text-color);">
            {{ $product->name }}
        </h3>

        {{-- Description (Conditional) --}}
        @if ($config['showDescription'] && $product->description)
            <p class="text-lg sm:text-xl lg:text-2xl opacity-90 leading-relaxed line-clamp-3 md:line-clamp-4 shadow-black text-shadow" style="color: var(--text-color);">
                {{ $product->description }}
            </p>
        @endif

        {{-- Price (Conditional) --}}
        @if ($config['showPrices'])
            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold pt-2" style="color: var(--accent-color);">
                {{ number_format($product->price, 2) }} DH
            </div>
        @endif
    </div>
</div>
