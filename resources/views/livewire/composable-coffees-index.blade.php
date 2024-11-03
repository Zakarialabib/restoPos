<div>
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="bg-retro-cream min-h-screen w-full mx-auto mb-6" x-data="{
        step: @entangle('step'),
        selectedSugar: @entangle('selectedSugar'),
        selectedBase: @entangle('selectedBase'),
        selectedAddons: @entangle('selectedAddons'),
        selectedCoffees: @entangle('selectedCoffees'),
        cart: @entangle('cart'),
        customerName: @entangle('customerName'),
        customerPhone: @entangle('customerPhone'),
        showCheckout: @entangle('showCheckout'),
        showSuccess: @entangle('showSuccess'),
        order: @entangle('order'),
        loading: false
    }">
        <p class="text-2xl md:text-3xl font-semibold mb-6 text-center">
            {{ __('Follow the steps to create your perfect coffee blend.') }}
        </p>

        <!-- Onboarding Section -->
        @if (empty($composableCoffees))
            <div class="mb-12">
                <h3 class="text-2xl font-semibold mb-4 text-retro-orange">
                    {{ __('Popular Composed Coffees') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($composableCoffees->take(4) as $coffee)
                        <div
                            class="bg-retro-yellow rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                            <img src="{{ $coffee->image }}" alt="{{ $coffee->name }}" class="w-full h-48 object-cover"
                                onerror="this.onerror=null">
                            <div class="p-4">
                                <h4 class="text-xl font-semibold text-retro-blue mb-2">{{ $coffee->name }}</h4>
                                <p class="text-retro-green mb-3">{{ $coffee->description }}</p>
                                <p class="text-retro-orange font-bold text-lg">{{ $coffee->price }}DH</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-8 my-4 p-6">
            <!-- Sidebar: Cart -->
            <div class="lg:w-1/4 order-2 lg:order-1">
                <div class="border-retro-orange border-solid border-4 bg-white rounded-lg shadow-lg px-6">
                    <h3 class="text-2xl text-retro-orange font-bold mb-4">
                        {{ __('Your Coffee') }}
                    </h3>
                    <div class="flex flex-col gap-y-6 rounded-lg mb-6 text-retro-blue">
                        @if (count($selectedCoffees) > 0)
                            <div>
                                <span class="font-medium">
                                    {{ __('Coffee') }}:
                                </span>
                                <p class="text-sm mb-1">
                                    {{ implode(', ', $this->coffees->whereIn('id', $selectedCoffees)->pluck('name')->toArray()) }}
                                </p>
                            </div>
                        @endif
                        @if ($selectedBase)
                            <div>
                                <span class="font-medium">
                                    {{ __('Base') }}:
                                </span>
                                <p class="text-sm mb-1">
                                    {{ $selectedBase }}
                                </p>
                            </div>
                        @endif
                        @if ($selectedSugar)
                            <div>
                                <span class="font-medium">
                                    {{ __('Sugar') }}:
                                </span>
                                <p class="text-sm mb-1"> {{ $selectedSugar }}</p>
                            </div>
                        @endif
                        @if (count($selectedAddons) > 0)
                            <div>
                                <span class="font-medium">
                                    {{ __('Add-ons') }}:
                                </span>
                                <p class="text-sm">
                                    {{ implode(', ', $selectedAddons) }}</p>
                            </div>
                        @endif
                        @if (count($selectedCoffees) === 0 && !$selectedBase && !$selectedSugar && count($selectedAddons) === 0)
                            <p class="text-sm text-retro-orange">
                                {{ __('Start composing your juice!') }}
                            </p>
                        @endif
                    </div>
                    <h4 class="text-xl text-retro-orange font-bold mb-3">
                        {{ __('Cart') }}
                    </h4>
                    <div class="space-y-3 mb-6">
                        @forelse ($cart as $index => $item)
                            <div class="flex justify-between items-center py-2 border-b border-retro-cream">
                                <div>
                                    <h5 class="font-medium text-retro-blue">{{ $item['name'] }}</h5>
                                    <p class="text-sm text-retro-blue">
                                        {{ __('Qty') }}:
                                        <input type="number" wire:model="cart.{{ $index }}.quantity"
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                            class="w-16 p-1 border rounded" min="1">
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-retro-blue">
                                        {{ number_format($item['price'] * $item['quantity'], 2) }}DH
                                    </p>
                                    <button class="bg-red-600 text-white p-2 rounded-full text-xs"
                                        wire:click="removeFromCart({{ $index }})" type="button"
                                        wire:loading.attr="disabled" wire:loading.class="opacity-50">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-retro-blue text-sm">
                                {{ __('Your cart is empty.') }}
                            </p>
                        @endforelse
                    </div>
                    <div class="flex justify-between items-center text-retro-blue font-bold text-lg mb-6">
                        <span>
                            {{ __('Total:') }}
                        </span>
                        <span>
                            {{ number_format($this->cartTotal, 2) }}DH
                        </span>
                    </div>
                    @if (count($cart) > 0)
                        <button type="button" wire:click="toggleCheckout"
                            class="w-full bg-retro-orange text-white py-2 px-4 rounded-full hover:bg-retro-yellow hover:text-retro-blue transition duration-300"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            {{ __('Proceed to Checkout') }}
                        </button>

                        @if ($showCheckout)
                            <div class="hidden md:flex">
                                <div class="gap-4 mt-6 w-full grid grid-cols-1 gap-y-6 justify-center items-center">
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
                            </div>
                            <button type="button" wire:click="placeOrder"
                                class="w-full bg-retro-blue text-white py-2 px-4 rounded-full hover:bg-retro-yellow hover:text-retro-green transition duration-300">
                                {{ __('Place Order') }}
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            @if ($showSuccess)
                <div class="border-retro-orange border-solid border-4 text-white rounded-lg shadow-lg p-6">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-bold mt-4 text-black">
                            {{ __('Order Summary') }}
                        </h3>

                        @if (!empty($order))
                            <div class="mt-2">
                                <p class="text-gray-700">
                                    {{ __('Thank you for your order,') }}
                                    {{ $order?->customer_name }}!
                                    {{ __('Here are the details of your order:') }}
                                </p>
                                <div class="space-y-4">
                                    @foreach ($order->items as $item)
                                        <div class="flex items-center justify-between border px-4">
                                            <div class="w-1/2">
                                                <h4 class="text-lg font-semibold text-black">
                                                    {{ $item->name }}
                                                </h4>
                                                <p class="text-gray-600">
                                                    {{ __('Quantity:') }}
                                                    {{ $item->quantity }}
                                                </p>
                                                <p class="text-gray-600">
                                                    {{ __('Price:') }}
                                                    {{ $item->price }}DH
                                                </p>
                                            </div>
                                            <div class="w-1/2">
                                                <p class="text-gray-600">
                                                    {{ __('Ingredients:') }}
                                                </p>
                                                <div class="flex flex-col text-retro-blue">
                                                    @php
                                                        $ingredients = $item->details;
                                                    @endphp
                                                    <o><strong>
                                                            {{ __('Coffees:') }}
                                                        </strong>
                                                        {{ implode(', ', $ingredients['coffees']) }}
                                                    </o>
                                                    <o><strong>
                                                            {{ __('Base:') }}
                                                        </strong>
                                                        {{ $ingredients['base'] }}
                                                    </o>
                                                    <o><strong>
                                                            {{ __('Sugar:') }}
                                                        </strong>
                                                        {{ $ingredients['sugar'] }}
                                                    </o>
                                                    <o><strong>
                                                            {{ __('Add-ons:') }}
                                                        </strong>
                                                        {{ implode(', ', $ingredients['addons']) }}
                                                    </o>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <h3 class="text-xl font-bold mt-4 text-black">Total:
                                    {{ $order?->total_amount }}DH
                                </h3>
                            </div>
                        @endif
                    </div>
                    <div class="w-full flex justify-center">
                        <x-button color="primaryOutline" wire:click="close" type="button" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50">
                            {{ __('Close') }}
                        </x-button>
                    </div>
                </div>
            @else
                <!-- Main Content: Stepper -->
                <div class="lg:w-3/4 bg-white order-1 lg:order-2 border-retro-orange border-solid border-4 rounded-lg p-4">
                    <nav class="grid grid-cols-2 md:grid-cols-4 gap-6 justify-center mb-8">
                        @foreach ($this->steps as $index => $stepName)
                            <div class="step-wrapper flex flex-col items-center">
                                <!-- Step Circle -->
                                <button wire:click="$set('step', {{ $index + 1 }})" type="button"
                                    class="step-button flex items-center justify-center w-10 h-10 rounded-full border-2 mb-2 transition-all duration-300
                                        @if ($step === $index + 1) bg-retro-orange text-white border-retro-orange
                                        @elseif($step > $index + 1) 
                                            bg-green-500 text-white border-green-500
                                        @else
                                            text-retro-blue border-retro-blue hover:bg-retro-orange hover:text-white hover:border-retro-orange @endif">

                                    <!-- Checkmark or Step Number -->
                                    @if ($step > $index + 1)
                                        <!-- Completed Step -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <!-- Step Number -->
                                        {{ $index + 1 }}
                                    @endif
                                </button>

                                <!-- Step Name -->
                                <span
                                    class="text-sm text-center transition-all duration-300
                                    @if ($step === $index + 1) text-retro-orange font-semibold
                                    @elseif($step > $index + 1) 
                                        text-green-500 font-semibold
                                    @else
                                        text-retro-blue @endif">
                                    {{ $stepName }}
                                </span>

                                <!-- Progress Bar (Active step only) -->
                                <div class="relative w-full h-0.5 bg-gray-200 mt-2">
                                    <div class="absolute left-0 h-0.5 bg-retro-orange transition-transform duration-300"
                                        style="width: {{ $step === $index + 1 ? '100%' : '0' }};">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </nav>

                    <!-- Step Content -->
                    <div>
                        <div x-show="step === 1">
                            <div class="flex justify-between items-center my-2">
                                <h3 class="text-2xl font-semibold text-retro-blue">
                                    {{ __('Select Your Coffees') }}
                                </h3>
                                <input type="text" wire:model.live="search"
                                    class="w-1/2 p-2 border border-retro-orange rounded-md text-gray-800 bg-transparent placeholder-gray-800 focus:outline-none focus:ring-2 focus:ring-retro-orange"
                                    placeholder="{{ __('Search for coffees') }}...">
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($this->coffees as $coffee)
                                    <div wire:key="{{ $coffee->id }}">
                                        <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden  cursor-pointer"
                                            wire:click="toggleCoffee({{ $coffee->id }})"
                                            x-bind:class="{
                                                'ring-4 ring-retro-orange': @js(in_array($coffee->id, $selectedCoffees))
                                            }">
                                            @if ($coffee->image)
                                                <img src="{{ $coffee->image }}" alt="{{ $coffee->name }}"
                                                    class="w-full h-32 object-cover">
                                            @endif
                                            <h4 class="text-lg text-center font-semibold text-retro-blue">
                                                {{ $coffee->name }}
                                            </h4>

                                            @if (in_array($coffee->id, $selectedCoffees))
                                                <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                                    <svg class="w-4 h-4 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
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

                        <!-- Step 2: Choose Base -->
                        <div x-show="step === 2">
                            <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                                {{ __('Select Your Base') }}
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                @foreach ($this->bases as $base)
                                    <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all overflow-hidden cursor-pointer"
                                        :class="{ 'ring-4 ring-retro-orange': selectedBase === '{{ $base }}' }"
                                        wire:click="$set('selectedBase', '{{ $base }}')">
                                        <h4 class="text-lg font-semibold text-retro-blue text-center">
                                            {{ $base }}
                                        </h4>
                                        @if ($selectedBase === $base)
                                            <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Sugar Preference -->
                        <div x-show="step === 3">
                            <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                                {{ __('Sugar Preference') }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                @foreach ($this->sugars as $sugarOption)
                                    <div class="relative bg-white p-4 rounded-lg shadow hover:shadow-lg transition-all duration-300  cursor-pointer"
                                        wire:click="$set('selectedSugar', '{{ $sugarOption }}')"
                                        :class="{ 'ring-4 ring-retro-orange': selectedSugar === '{{ $sugarOption }}' }">

                                        <h4 class="text-lg font-semibold text-retro-blue text-center">
                                            {{ $sugarOption }}
                                        </h4>

                                        @if ($selectedSugar === $sugarOption)
                                            <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 4: Add-ons -->
                        <div x-show="step === 4">
                            <h3 class="text-2xl font-semibold mb-6 text-retro-blue">
                                {{ __('Select Add-ons') }}
                            </h3>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($this->addons as $addon)
                                    <div class="relative cursor-pointer"
                                        wire:click="toggleAddon('{{ $addon }}')">
                                        <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition-all duration-300"
                                            :class="{ 'ring-4 ring-retro-orange': {{ in_array($addon, $selectedAddons) }} }">
                                            <h4 class="text-lg font-semibold text-center text-retro-blue">
                                                {{ $addon }}</h4>
                                            @if (in_array($addon, $selectedAddons))
                                                <div class="absolute top-2 right-2 bg-retro-orange rounded-full p-1">
                                                    <svg class="w-4 h-4 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
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
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button wire:click="previousStep"
                            class="bg-retro-blue text-white px-6 py-3 rounded-full hover:bg-retro-cream hover:text-retro-blue border-2 border-retro-blue transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-retro-blue"
                            :disabled="step === 1" x-show="step > 1" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50">
                            {{ __('Previous') }}
                        </button>
                        <button wire:click="nextStep"
                            class="bg-retro-orange text-white px-6 py-3 rounded-full hover:bg-retro-yellow hover:text-retro-blue border-2 border-retro-orange transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-retro-orange"
                            x-show="step < 4" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            {{ __('Next') }}
                        </button>

                        <button wire:click="addToCart"
                            class="bg-retro-green text-white px-6 py-3 rounded-full hover:bg-retro-yellow hover:text-retro-green border-2 border-retro-green transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-retro-green"
                            x-show="step === 4" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                            {{ __('Add to Cart') }}
                        </button>

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
