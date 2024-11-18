<div>
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="bg-retro-cream min-h-screen w-full mx-auto mb-6" x-data="{
        step: @entangle('step'),
        selectedSugar: @entangle('selectedSugar'),
        selectedBase: @entangle('selectedBase'),
        selectedAddons: @entangle('selectedAddons'),
        selectedFruits: @entangle('selectedFruits'),
        cart: @entangle('cart'),
        {{-- customerName: @entangle('customerName'),
        customerPhone: @entangle('customerPhone'), --}}
        selectedSize: @entangle('selectedSize'),
        showSuccess: @entangle('showSuccess'),
        order: @entangle('order'),
    }">
        <p class="text-2xl md:text-3xl font-semibold mb-6 text-center">
            {{ __('Follow the steps to create your perfect juice blend. 🍹') }}
        </p>

        <!-- Onboarding Section -->
        @if (!empty($composableJuices))
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-4 text-retro-orange">
                    {{ __('Popular Composed Juices 🍊') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- @foreach ($popularJuices as $juice)
                            <div class="border p-4 rounded-lg shadow-md bg-white">
                                <h4 class="text-xl font-bold">{{ $juice->name }}</h4>
                                <p>{{ $juice->description }}</p>
                                <p>{{ $juice->price }} DH</p>
                                <button wire:click="addToCart({{ $juice->id }})" class="bg-green-500 text-white px-4 py-2 rounded mt-2">Add to Cart 🛒</button>
                            </div>
                        @endforeach --}}
                </div>
            </div>
        @endif
        <div class="container mx-auto">
            @if (session()->has('success'))
                <x-alert type="success" :dismissal="false" :showIcon="true">
                    {{ session('success') }} 🎉
                </x-alert>
            @endif

            @if (session()->has('error'))
                <x-alert type="error" :dismissal="false" :showIcon="true">
                    {{ session('error') }} ❌
                </x-alert>
            @endif
        </div>
        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-8 my-4 p-6">

            <!-- Sidebar: Cart -->
            @if ($showSuccess)
                <div class="w-full bg-white border-retro-orange border-solid border-4 rounded-lg shadow-lg p-6">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-bold mt-4 text-black">
                            {{ __('Order Summary 📝') }}
                        </h3>
                        @if (!empty($order))
                            <div class="mt-2">
                                <p class="text-gray-700">
                                    {{ __('Thank you for your order,') }}
                                    {{ $order?->customer_name }}! 🎊
                                    {{ __('Here are the details of your order:') }}
                                </p>
                                {{-- customJuiceName --}}
                                <p class="text-gray-700">
                                    {{ $customJuiceName }}
                                </p>
                                <div class="space-y-4">
                                    @foreach ($order->items as $item)
                                        <div class="w-full">
                                            <p class="text-gray-600">
                                                {{ __('Ingredients:') }}
                                            </p>
                                            <div class="flex flex-col text-retro-blue">
                                                @php
                                                    $ingredients = json_decode($item->details);
                                                @endphp
                                                <div><strong>{{ __('Fruits: 🍏') }}</strong>
                                                    <div>
                                                        @foreach ($ingredients->fruits as $fruit)
                                                            <p>
                                                                {{ $fruit->name }}
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div><strong>{{ __('Base: 🥛') }}</strong>
                                                    <p>
                                                        {{ $ingredients->base }}
                                                    </p>
                                                </div>
                                                <div><strong>{{ __('Sugar: 🍬') }}</strong>
                                                    <p>
                                                        {{ $ingredients->sugar }}
                                                    </p>
                                                </div>
                                                <div><strong>{{ __('Add-ons: 🌟') }}</strong>
                                                    @if (!empty($ingredients->addons))
                                                        <ul>
                                                            @foreach ($ingredients->addons as $addon)
                                                                <li>
                                                                    {{ $addon->name }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p>{{ __('No add-ons selected') }}</p>
                                                    @endif
                                                </div>

                                                <div><strong>{{ __('Size: 📏') }}</strong>
                                                    <p>
                                                        {{ $ingredients->size }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <h3 class="text-xl font-bold mt-4 text-black">Total: 💰
                                    {{ $order?->total_amount }} DH
                                </h3>
                            </div>
                        @endif
                    </div>
                    <div class="w-full flex justify-center">
                        <x-button color="primaryOutline" wire:click="close" type="button">
                            {{ __('Close') }} ❌
                        </x-button>
                    </div>
                </div>
            @else
                <div class="lg:w-1/4 order-2 lg:order-1">
                    <div class="border-retro-orange border-solid border-4 bg-white rounded-lg shadow-lg px-6">
                        <h3 class="text-2xl text-retro-orange font-bold mb-4">
                            {{ __('Your Juice 🍹') }}
                        </h3>
                        <div class="flex flex-col gap-y-4 rounded-lg mb-6 text-retro-blue">
                            @if (count($selectedFruits) > 0)
                                <div>
                                    <span class="font-medium">
                                        {{ __('Fruit 🍏') }}:
                                    </span>
                                    <ol class="text-sm mb-1">
                                        @foreach ($this->fruits->whereIn('id', $selectedFruits)->pluck('name') as $fruit)
                                            <li>{{ $fruit }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                            @if ($selectedBase)
                                <div>
                                    <span class="font-medium">
                                        {{ __('Base 🥛') }}:
                                    </span>
                                    <p class="text-sm mb-1">
                                        {{ $selectedBase }}
                                    </p>
                                </div>
                            @endif
                            @if ($selectedSugar)
                                <div>
                                    <span class="font-medium">
                                        {{ __('Sugar 🍬') }}:
                                    </span>
                                    <p class="text-sm mb-1"> {{ $selectedSugar }}</p>
                                </div>
                            @endif
                            @if (count($selectedAddons) > 0)
                                <div>
                                    <span class="font-medium">
                                        {{ __('Add-ons 🌟') }}:
                                    </span>
                                    <ul class="text-sm mb-1">
                                        @foreach ($selectedAddons as $addon)
                                            <li>{{ $addon }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (!empty($selectedSize))
                                <div>
                                    <span class="font-medium">
                                        {{ __('Size 📏') }}:
                                    </span>
                                    <p class="capitalize text-sm mb-1">
                                        {{ $selectedSize }} - {{ $this->getSizeCapacity($selectedSize) }}
                                    </p>
                                </div>
                            @endif
                            @if (count($selectedFruits) === 0 && !$selectedBase && !$selectedSugar && count($selectedAddons) === 0)
                                <p class="text-retro-blue text-sm underline">
                                    {{ __('Start composing your juice! 🍹') }}
                                </p>
                            @endif
                        </div>
                        <h4 class="text-xl text-retro-orange font-bold mb-3">
                            {{ __('Cart 🛒') }}
                        </h4>
                        <div class="space-y-3 mb-6">
                            @forelse ($cart as $index => $item)
                                <div
                                    class="flex flex-col justify-between items-center py-2 border-b border-retro-cream">
                                    <p class="font-medium text-retro-blue">
                                        {{ $item['name'] }}
                                    </p>

                                    @if (!empty($item['ingredients']))
                                        <p class="text-sm text-retro-blue">
                                            {{ __('Ingredients 🍏') }}: <br>
                                            {{ __('fruits') }}:<br>
                                            @foreach ($item['ingredients']['fruits'] as $fruit)
                                                {{ $fruit['name'] }} <br>
                                            @endforeach
                                            {{ __('Base 🥛') }}: {{ $item['ingredients']['base'] }} <br>
                                            {{ __('Addons 🌟') }}:<br>
                                            @foreach ($item['ingredients']['addons'] as $addon)
                                                {{ $addon['name'] }} <br>
                                            @endforeach
                                            {{ __('Size 📏') }}: {{ $item['ingredients']['size'] }}
                                        </p>
                                    @endif

                                    @if (!empty($item['size']))
                                        <p class="text-sm text-retro-blue">
                                            {{ __('Size 📏') }}: {{ $item['size'] }}
                                        </p>
                                    @endif

                                    @if (!empty($item['quantity']))
                                        <p class="text-sm text-retro-blue">
                                            {{ __('Qty 📦') }}: {{ $item['quantity'] }}
                                        </p>
                                    @endif
                                    <div class="text-right">
                                        <p class="font-medium text-retro-blue">
                                            {{ number_format($item['price'] * $item['quantity'], 2) }} DH
                                        </p>
                                        <button class="bg-red-600 text-white p-2 rounded-full text-xs"
                                            wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                            wire:click="removeFromCart({{ $index }})" type="button"
                                            aria-label="{{ __('Remove item from cart') }}">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-retro-blue text-sm underline mb-6">
                                    {{ __('Your cart is empty. 🛒') }}
                                </p>
                            @endforelse
                        </div>
                        <div
                            class="flex justify-between items-center text-retro-blue font-bold text-lg mb-6 border-t border-2 border-retro-orange pt-6">
                            <span>
                                {{ __('Total: 💰') }}
                            </span>
                            <span>
                                {{ $this->cartTotal, 2 }} DH
                            </span>
                        </div>

                        @if (count($cart) > 0)
                            {{-- <div class="hidden md:flex">
                                <div
                                    class="gap-4 mt-6 w-full grid grid-cols-1 gap-y-6 justify-center items-center mb-4">
                                    <div>
                                        <label for="customerName" class="block text-sm font-medium text-gray-700">
                                            {{ __('Name') }}
                                        </label>
                                        <input type="text" id="customerName" wire:model="customerName"
                                            class="mt-1 py-2 border rounded w-full">
                                        @error('customerName')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="customerPhone"
                                            class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                        <input type="tel" id="customerPhone" wire:model="customerPhone"
                                            class="mt-1 py-2 border rounded w-full">
                                        @error('customerPhone')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <button type="button" wire:click="placeOrder"
                                class="w-full mb-4 bg-retro-orange text-white py-2 px-4 rounded-full hover:bg-retro-orange hover:text-retro-orange transition duration-300">
                                {{ __('Confirm Order ✅') }}
                            </button>
                        @endif
                    </div>
                </div>
                <!-- Main Content: Stepper -->
                <div
                    class="lg:w-3/4 bg-white order-1 lg:order-2 border-retro-orange border-solid border-4 rounded-lg p-4">
                    <nav class="grid grid-cols-2 md:grid-cols-5 gap-8 justify-center mb-12 px-6">
                        @foreach ($this->steps as $index => $stepName)
                            <div class="w-full flex-grow relative">
                                <button wire:click="$set('step', {{ $index + 1 }})" type="button"
                                    class="w-full flex flex-col items-center gap-4 p-6 rounded-2xl transition-all duration-300 transform hover:scale-105
                                        @if ($step === $index + 1) bg-retro-orange text-white scale-110
                                        @elseif($step > $index + 1) bg-green-500 text-white
                                        @else bg-white text-retro-blue hover:bg-retro-cream @endif
                                        shadow-xl border-4 border-retro-orange">

                                    <!-- Larger Step Emoji/Icon -->
                                    <span class="text-6xl mb-2">
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
                                    <span class="text-xl font-bold">Step {{ $index + 1 }}</span>

                                    <!-- Larger Step Name -->
                                    <span class="text-lg text-center font-medium">{{ $stepName }}</span>

                                    <!-- Larger Completion Check -->
                                    @if ($step > $index + 1)
                                        <span
                                            class="absolute -top-3 -right-3 bg-green-500 rounded-full p-3 text-white text-2xl">
                                            ✓
                                        </span>
                                    @endif
                                </button>
                            </div>
                        @endforeach
                    </nav>

                    <!-- Step Content -->
                    <div x-show="step === 1">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                            <h3 class="text-4xl font-bold text-retro-blue flex items-center gap-4">
                                <span class="text-7xl">🍓</span>
                                <span>{{ __('Select Your Fruits 🍏') }}</span>
                            </h3>

                            <div class="w-full md:w-1/3">
                                <input type="text" wire:model.live="search"
                                    class="w-full p-4 text-xl border-4 border-retro-orange rounded-full text-gray-800 bg-white placeholder-gray-600 focus:outline-none focus:ring-4 focus:ring-retro-orange"
                                    placeholder="🔍 Search fruits...">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            @foreach ($this->fruits as $fruit)
                                <div wire:key="{{ $fruit->id }}"
                                    class="transform transition-all duration-300 hover:scale-105">
                                    <div class="relative bg-white rounded-3xl overflow-hidden cursor-pointer shadow-2xl border-4 border-retro-orange
                                        {{ in_array($fruit->id, $selectedFruits) ? 'ring-8 ring-retro-orange' : '' }}"
                                        wire:click="toggleFruit({{ $fruit->id }})">

                                        <div class="relative h-48 overflow-hidden">
                                            <img src="{{ $fruit->image }}" alt="{{ $fruit->name }}"
                                                class="w-full h-full object-cover transform transition-transform duration-300 hover:scale-110">
                                            @if (in_array($fruit->id, $selectedFruits))
                                                <div
                                                    class="absolute inset-0 bg-retro-orange bg-opacity-30 flex items-center justify-center">
                                                    <span class="text-7xl">✓</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-6 text-center">
                                            <h4 class="text-2xl font-bold text-retro-blue">
                                                {{ $fruit->name }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="step === 2">
                        <h3 class="text-4xl font-bold mb-8 text-retro-blue flex items-center gap-4">
                            <span class="text-7xl">🥤</span>
                            <span>{{ __('Select Your Base 🥛') }}</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                            @foreach ($this->bases as $base)
                                <div class="transform transition-all duration-300 hover:scale-105">
                                    <div class="relative bg-white p-8 rounded-3xl cursor-pointer shadow-2xl border-4 border-retro-orange
                                        {{ $selectedBase === $base ? 'ring-8 ring-retro-orange' : '' }}"
                                        wire:click="toggleBase('{{ $base }}')">

                                        <div class="text-center">
                                            <span class="text-8xl mb-6 block">
                                                @if ($base === __('Water'))
                                                    💧
                                                @elseif ($base === __('Orange juice'))
                                                    🍊
                                                @elseif ($base === 'Milk')
                                                    🥛
                                                @else
                                                    🍋
                                                @endif
                                            </span>
                                            <h4 class="text-3xl font-bold text-retro-blue">{{ $base }}</h4>
                                        </div>

                                        @if ($selectedBase === $base)
                                            <span class="absolute top-4 right-4 text-4xl">✓</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="step === 3">
                        <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                            {{ __('Sugar Preference 🍬') }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                            @foreach ($this->sugars as $sugarOption)
                                <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden cursor-pointer  border-solid border-2 border-black"
                                    wire:click="toggleSugar('{{ $sugarOption }}')"
                                    x-bind:class="{
                                        'ring-4 ring-retro-orange': @js($sugarOption) === @js($selectedSugar)
                                    }">
                                    <h4 class="text-lg font-semibold text-retro-blue text-center">
                                        {{ $sugarOption }}
                                    </h4>

                                    @if ($selectedSugar === $sugarOption)
                                        <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="step === 4">
                        <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                            {{ __('Select Add-ons 🌟') }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                            @foreach ($this->addons as $addon)
                                <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden cursor-pointer  border-solid border-2 border-black"
                                    wire:click="toggleAddon('{{ $addon }}')"
                                    x-bind:class="{
                                        'ring-4 ring-retro-orange': selectedAddons.includes(
                                            '{{ $addon }}')
                                    }">
                                    <h4 class="text-lg font-semibold text-center text-retro-blue">
                                        {{ $addon }}
                                    </h4>
                                    @if (in_array($addon, $selectedAddons))
                                        <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add this new step content after the add-ons step -->
                    <div x-show="step === 5">
                        <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                            {{ __('Select Size 📏') }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach ($this->sizes as $sizeKey => $size)
                                <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden cursor-pointer  border-solid border-2 border-black"
                                    wire:click="toggleSize('{{ $sizeKey }}')" wire:key="{{ $sizeKey }}">
                                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-all duration-300"
                                        x-bind:class="{ 'ring-4 ring-retro-orange': @js($sizeKey) === @js($selectedSize) }">
                                        <h4 class="text-xl font-semibold text-center text-retro-blue mb-2">
                                            {{ $size['name'] }}
                                        </h4>
                                        <p class="text-center text-retro-blue mb-2">
                                            {{ $size['capacity'] }}
                                        </p>
                                        @if ($selectedSize === $sizeKey)
                                            <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-12 mb-6">
                        <button wire:click="previousStep"
                            class="flex items-center gap-4 bg-retro-blue text-white px-8 py-4 text-xl rounded-full hover:bg-retro-cream hover:text-retro-blue border-4 border-retro-blue transition-all duration-300 transform hover:scale-105"
                            :disabled="step === 1" x-show="step > 1">
                            <span class="text-3xl">👈</span>
                            <span class="font-bold">{{ __('Previous') }}</span>
                        </button>

                        <button wire:click="nextStep"
                            class="flex items-center gap-4 bg-retro-orange text-white px-8 py-4 text-xl rounded-full hover:bg-retro-yellow hover:text-retro-blue border-4 border-retro-orange transition-all duration-300 transform hover:scale-105"
                            x-show="step < 5">
                            <span class="font-bold">{{ __('Next') }}</span>
                            <span class="text-3xl">👉</span>
                        </button>

                        <button wire:click="addToCart"
                            class="flex items-center gap-4 bg-retro-green text-white px-8 py-4 text-xl rounded-full hover:bg-retro-yellow hover:text-retro-green border-4 border-retro-green transition-all duration-300 transform hover:scale-105"
                            x-show="step === 5">
                            <span wire:loading.remove>
                                <span class="text-3xl">🛒</span>
                                <span class="font-bold">{{ __('Add to Cart') }}</span>
                            </span>
                            <span wire:loading>
                                <span class="text-3xl">⏳</span>
                                <span class="font-bold">{{ __('Adding...') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
