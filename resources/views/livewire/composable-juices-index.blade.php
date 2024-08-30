<div>
    <div class="container mx-auto p-4" x-data="{ step: @entangle('step') }">
        <h2 class="text-3xl font-bold mb-4 text-center">Compose Your Juice</h2>
        <p class="text-center mb-8">Follow the steps to create your perfect juice blend.</p>

        <!-- Onboarding Section -->
        <div class="onboarding mb-8">
            <h3 class="text-xl font-semibold mb-2">Popular Composed Juices</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($composableJuices->take(4) as $juice)
                    <div class="composed-juice-card border p-4 rounded-lg shadow">
                        <img src="{{ $juice->image_url }}" alt="{{ $juice->name }}"
                            class="w-full h-32 object-cover rounded">
                        <h4 class="text-lg font-semibold mt-2">{{ $juice->name }}</h4>
                        <p class="text-gray-600">{{ $juice->description }}</p>
                        <p class="text-green-500 font-bold">{{ $juice->price }}DH</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex">
            <!-- Sidebar: Cart (1/4 width) -->
            <div class="w-1/4 pr-4">
                <div class="bg-white shadow-md rounded-lg px-6 py-2">
                    <h3 class="text-2xl font-semibold">Your Juice</h3>
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Fruit:</span>
                            {{ implode(', ', $selectedFruits) }}</p>
                        <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Base:</span> {{ $selectedBase }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Sugar:</span>
                            {{ $selectedSugar }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Add-ons:</span>
                            {{ implode(', ', $selectedAddons) }}</p>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">Cart</h4>
                    <div class="space-y-3 mb-6">
                        @forelse ($cart as $index => $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div>
                                    <h5 class="font-medium">{{ $item['name'] }}</h5>
                                    <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                    <button wire:click="removeFromCart({{ $index }})"
                                        class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Your cart is empty.</p>
                        @endforelse
                    </div>
                    <div class="flex justify-between items-center font-semibold text-lg mb-6">
                        <span>Total:</span>
                        <span>{{ number_format(array_reduce($cart,function ($carry, $item) {return $carry + $item['price'] * $item['quantity'];},0),2) }}DH</span>
                    </div>
                    <div class="flex justify-center">
                        <a href="{{ route('checkout') }}"
                            class="block mb-4 w-full bg-green-500 text-white text-center px-2 py-3 rounded-lg hover:bg-green-600 transition duration-300">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content: Stepper (3/4 width) -->
            <div class="w-3/4">

                <div class="flex justify-center mb-8">
                    <nav class="flex space-x-4">
                        <button wire:click="$set('step', 1)"
                            class="step-button relative px-4 py-2 text-gray-500 transition-colors duration-300 hover:text-blue-500"
                            :class="{ 'text-blue-500': step === 1 }">
                            <span>1. Select Your Fruits</span>
                            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 transform scale-x-0 transition-transform duration-300"
                                :class="{ 'scale-x-100': step === 1 }"></div>
                        </button>
                        <button wire:click="$set('step', 2)"
                            class="step-button relative px-4 py-2 text-gray-500 transition-colors duration-300 hover:text-blue-500"
                            :class="{ 'text-blue-500': step === 2 }">
                            <span>2. Select Your Base</span>
                            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 transform scale-x-0 transition-transform duration-300"
                                :class="{ 'scale-x-100': step === 2 }"></div>
                        </button>
                        <button wire:click="$set('step', 3)"
                            class="step-button relative px-4 py-2 text-gray-500 transition-colors duration-300 hover:text-blue-500"
                            :class="{ 'text-blue-500': step === 3 }">
                            <span>3. Do You Want Sugar?</span>
                            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 transform scale-x-0 transition-transform duration-300"
                                :class="{ 'scale-x-100': step === 3 }"></div>
                        </button>
                        <button wire:click="$set('step', 4)"
                            class="step-button relative px-4 py-2 text-gray-500 transition-colors duration-300 hover:text-blue-500"
                            :class="{ 'text-blue-500': step === 4 }">
                            <span>4. Select Add-ons</span>
                            <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 transform scale-x-0 transition-transform duration-300"
                                :class="{ 'scale-x-100': step === 4 }"></div>
                        </button>
                    </nav>
                </div>

                <!-- Step Content -->
                <div>
                    <template x-if="step === 1">
                        <div>
                            <h3 class="text-xl font-semibold mb-2 text-black">Select Your Fruits</h3>
                            <div class="relative">
                                <div class="flex overflow-x-auto space-x-4 pb-4">
                                    @foreach ($fruits as $fruit)
                                        <div class="fruit-card border p-4 rounded-lg shadow cursor-pointer flex-shrink-0 hover:shadow-lg transition-shadow duration-300"
                                            wire:click="toggleFruit('{{ $fruit->name }}')"
                                            :class="{ 'border-blue-500': selectedFruits.includes('{{ $fruit->name }}') }">
                                            <img src="{{ $fruit->image_url }}" alt="{{ $fruit->name }}"
                                                class="w-32 h-32 object-cover rounded">
                                            <h4 class="text-lg font-semibold mt-2 text-black">{{ $fruit->name }}</h4>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-between mt-4">
                                <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                                    disabled>Previous</button>
                                <button wire:click="nextStep"
                                    class="bg-black text-white px-4 py-2 rounded hover:bg-white hover:text-black border border-black transition-colors duration-300">Next</button>
                            </div>
                        </div>
                    </template>

                    <template x-if="step === 2">
                        <div>
                            <h3 class="text-2xl font-bold mb-6 text-black">Select Your Base</h3>
                            <div class="w-full flex flex-row justify-around items-center gap-6">
                                @foreach ($bases as $base)
                                    <div class="w-1/3 h-49 flex items-center relative overflow-hidden rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 cursor-pointer"
                                        wire:click="$set('selectedBase', '{{ $base }}')"
                                        :class="{ 'ring-4 ring-red-500': selectedBase === '{{ $base }}' }">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-white/60 to-white/30 backdrop-blur-sm z-10">
                                        </div>
                                        <img src="{{ asset('images/' . strtolower(str_replace(' ', '-', $base)) . '.jpg') }}"
                                            alt="{{ $base }}" class="w-full h-48 object-cover">
                                        <div
                                            class="absolute bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-sm z-20">
                                            <h4 class="text-lg font-semibold text-black">{{ $base }}</h4>
                                        </div>
                                        <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 z-30 transition-opacity duration-300"
                                            :class="{ 'opacity-100': selectedBase === '{{ $base }}', 'opacity-0': selectedBase !== '{{ $base }}' }">
                                            <svg class="w-4 h-4 text-white mx-auto my-1" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-8">
                                <button wire:click="previousStep"
                                    class="bg-black text-white px-6 py-3 rounded-full hover:bg-white hover:text-black border-2 border-black transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">Previous</button>
                                <button wire:click="nextStep"
                                    class="bg-black text-white px-6 py-3 rounded-full hover:bg-white hover:text-black border-2 border-black transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">Next</button>
                            </div>
                        </div>
                    </template>

                    <template x-if="step === 3">
                        <div>
                            <h3 class="text-2xl font-bold mb-6 text-black">Sugar Preference</h3>
                            <div class="flex flex-row justify-center items-center gap-6">
                                <div class="w-full sm:w-1/2 h-40 flex items-center relative overflow-hidden rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 cursor-pointer"
                                    wire:click="$set('selectedSugar', 'Yes')"
                                    :class="{ 'ring-4 ring-red-500': selectedSugar === 'Yes' }">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-white/60 to-white/30 backdrop-blur-sm z-10">
                                    </div>
                                    <img src="{{ asset('images/sugar.jpg') }}" alt="Sugar"
                                        class="w-full h-40 object-cover">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-sm z-20">
                                        <h4 class="text-lg font-semibold text-black">Yes, I want sugar</h4>
                                    </div>
                                    <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 z-30 transition-opacity duration-300"
                                        :class="{ 'opacity-100': selectedSugar === 'Yes', 'opacity-0': selectedSugar !== 'Yes' }">
                                        <svg class="w-4 h-4 text-white mx-auto my-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="w-full sm:w-1/2 h-40 flex items-center relative overflow-hidden rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 cursor-pointer"
                                    wire:click="$set('selectedSugar', 'No')"
                                    :class="{ 'ring-4 ring-red-500': selectedSugar === 'No' }">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-white/60 to-white/30 backdrop-blur-sm z-10">
                                    </div>
                                    <img src="{{ asset('images/no-sugar.jpg') }}" alt="No Sugar"
                                        class="w-full h-40 object-cover">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-sm z-20">
                                        <h4 class="text-lg font-semibold text-black">No sugar, please</h4>
                                    </div>
                                    <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 z-30 transition-opacity duration-300"
                                        :class="{ 'opacity-100': selectedSugar === 'No', 'opacity-0': selectedSugar !== 'No' }">
                                        <svg class="w-4 h-4 text-white mx-auto my-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between mt-8">
                                <button wire:click="previousStep"
                                    class="bg-black text-white px-6 py-3 rounded-full hover:bg-white hover:text-black border-2 border-black transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">Previous</button>
                                <button wire:click="nextStep"
                                    class="bg-black text-white px-6 py-3 rounded-full hover:bg-white hover:text-black border-2 border-black transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">Next</button>
                            </div>
                        </div>
                    </template>

                    <template x-if="step === 4">
                        <div>
                            <h3 class="text-xl font-semibold mb-2 text-black">Select Add-ons</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($addons as $addon)
                                    <div class="border p-4 rounded-lg shadow cursor-pointer hover:shadow-lg transition-shadow duration-300"
                                        wire:click="toggleAddon('{{ $addon }}')"
                                        x-data="{ isSelected: @entangle('selectedAddons').('{{ $addon }}') }"
                                        :class="{ 'border-blue-500': isSelected }">
                                        <h4 class="text-lg font-semibold text-black">{{ $addon }}</h4>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-4">
                                <button wire:click="previousStep"
                                    class="bg-black text-white px-4 py-2 rounded hover:bg-white hover:text-black border border-black transition-colors duration-300">Previous</button>
                                <button wire:click="addToCart"
                                    class="bg-black text-white px-4 py-2 rounded hover:bg-white hover:text-black border border-black transition-colors duration-300">Next</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
