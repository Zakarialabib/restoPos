<div>
    <div class="min-h-screen min-w-screen relative overflow-hidden transition-colors duration-700 bg-gray-900 text-white" x-data="{
        isFullScreen: false,
        isTransitioning: false,
        currentSlideIndex: 0,
        activeIndex: 0,
        autoRotate: {{ $autoRotate ? 'true' : 'false' }},
        rotationInterval: {{ $rotationInterval ?? 10000 }},
        autoRotateInterval: null,
    
        init() {
            this.setupKeyboardShortcuts();
            this.applyThemeVariables();
            this.setupAutoRotate();
        },
    
        setupAutoRotate() {
            if (this.autoRotateInterval) {
                clearInterval(this.autoRotateInterval);
            }
    
            if (this.autoRotate) {
                this.autoRotateInterval = setInterval(() => {
                    this.rotateDisplayMode();
                }, this.rotationInterval);
            }
        },
    
        toggleAutoRotate() {
            this.autoRotate = !this.autoRotate;
            this.setupAutoRotate();
        },
    
        rotateDisplayMode() {
            const modes = Object.keys(@js($displayModes));
            const currentIndex = modes.indexOf($wire.displayMode);
            const nextIndex = (currentIndex + 1) % modes.length;
    
            // Set transitioning state for animation
            this.isTransitioning = true;
    
            // Change display mode after a short delay for transition effect
            setTimeout(() => {
                $wire.setDisplayMode(modes[nextIndex]);
    
                // Reset transition state after mode change
                setTimeout(() => {
                    this.isTransitioning = false;
                }, 300);
            }, 200);
        },
    
        previousDisplayMode() {
            const modes = Object.keys(@js($displayModes));
            const currentIndex = modes.indexOf($wire.displayMode);
            const prevIndex = (currentIndex - 1 + modes.length) % modes.length;
    
            $wire.setDisplayMode(modes[prevIndex]);
        },
    
        setupKeyboardShortcuts() {
            window.addEventListener('keydown', (e) => {
                // ESC to toggle settings
                if (e.key === 'Escape') {
                    $wire.toggleSettings();
                }
    
                // F for fullscreen
                if (e.key === 'f' || e.key === 'F') {
                    this.toggleFullScreen();
                }
    
                // M for mute/unmute
                if (e.key === 'm' || e.key === 'M') {
                    $wire.toggleMute();
                }
    
                // R for toggle auto-rotate
                if (e.key === 'r' || e.key === 'R') {
                    this.toggleAutoRotate();
                }
    
                // Arrow keys for navigation
                if (e.key === 'ArrowRight') {
                    this.rotateDisplayMode();
                }
                if (e.key === 'ArrowLeft') {
                    this.previousDisplayMode();
                }
            });
        },
    
        toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                this.isFullScreen = true;
            } else {
                document.exitFullscreen();
                this.isFullScreen = false;
            }
        },
    
        applyThemeVariables() {
            const theme = @js($themeConfig);
            document.documentElement.style.setProperty('--primary-color', theme.primary);
            document.documentElement.style.setProperty('--secondary-color', theme.secondary);
            document.documentElement.style.setProperty('--background-color', theme.background);
            document.documentElement.style.setProperty('--text-color', theme.text);
            document.documentElement.style.setProperty('--accent-color', theme.accent);
        },
    
        getThemeStyles() {
            const theme = @js($themeConfig);
            const effect = $wire.uiConfig.backgroundEffect;
    
            if (effect === 'gradient') {
                return {
                    background: `linear-gradient(to bottom right, ${theme.primary}, ${theme.background})`
                };
            } else if (effect === 'pattern') {
                return {
                    backgroundColor: theme.background,
                    backgroundImage: `radial-gradient(${theme.secondary}20 1px, transparent 1px)`,
                    backgroundSize: '20px 20px'
                };
            }
    
            return {
                backgroundColor: theme.background
            };
        },
    
        nextItem() {
            this.activeIndex = (this.activeIndex + 1) % this.getProductsForCurrentView().length;
        },
    
        prevItem() {
            const products = this.getProductsForCurrentView();
            this.activeIndex = (this.activeIndex - 1 + products.length) % products.length;
        },
    
        getProductsForCurrentView() {
            const products = @js($organizedProducts['all'] ?? []);
    
            if ($wire.displayMode === 'FEATURED_VIEW') {
                return products.filter(product => product.is_featured);
            }
    
            return products;
        }
    }"
        x-init="init();" x-bind:style="getThemeStyles()" x-bind:class="{ 'opacity-0 scale-95': isTransitioning }"
        class="transition-all duration-500 ease-in-out">

        <!-- Floating Clock and Temperature Display -->
        <div class="absolute top-6 right-6 z-20 flex items-center gap-4">
            @if ($uiConfig['showClock'])
                <div
                    class="bg-black/40 backdrop-blur-md rounded-xl px-4 py-2 flex items-center gap-3 shadow-lg border border-white/10 text-white hover:bg-black/50 transition-all duration-300">
                    <span class="material-icons text-white/80">schedule</span>
                    <span class="text-xl font-medium tracking-wider">{{ $currentTime }}</span>
                </div>
            @endif

            @if ($uiConfig['showTemperature'])
                <div
                    class="bg-black/40 backdrop-blur-md rounded-xl px-4 py-2 flex items-center gap-3 shadow-lg border border-white/10 text-white hover:bg-black/50 transition-all duration-300">
                    <span class="material-icons text-white/80">device_thermostat</span>
                    <span class="text-xl font-medium tracking-wider">23°C</span>
                </div>
            @endif
        </div>

        <!-- Restaurant Logo -->
        @if ($uiConfig['showLogo'])
            <div class="absolute top-6 left-6 z-20 flex items-center gap-3">
                <div class="bg-black/40 backdrop-blur-md rounded-xl p-3 shadow-lg border border-white/10">
                    <div class="{{ $uiConfig['logoSize'] === 'large' ? 'w-12 h-12' : 'w-10 h-10' }} flex items-center justify-center rounded-full"
                        style="background-color: var(--primary-color)">
                        <span
                            class="{{ $uiConfig['logoSize'] === 'large' ? 'text-2xl' : 'text-xl' }} font-bold text-white">R</span>
                    </div>
                </div>
                <div
                    class="bg-black/40 backdrop-blur-md rounded-xl px-4 py-2 shadow-lg border border-white/10 text-white">
                    <h1 class="{{ $uiConfig['logoSize'] === 'large' ? 'text-xl' : 'text-lg' }} font-bold tracking-wide">
                        RestoPos</h1>
                    <p class="text-white/70 text-xs tracking-wider">DIGITAL MENU</p>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="h-screen flex flex-col justify-center items-center px-4 py-4">
            <!-- GRID VIEW -->
            <div x-show="$wire.displayMode === 'GRID_VIEW'" 
                 x-transition:enter="menu-transition fade-enter" 
                 x-transition:enter-start="fade-enter" 
                 x-transition:enter-end="fade-enter-active" 
                 x-transition:leave="menu-transition fade-leave" 
                 x-transition:leave-start="fade-leave" 
                 x-transition:leave-end="fade-leave-active" 
                 class="w-full h-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6 overflow-y-auto">
                @foreach ($organizedProducts['all'] as $product)
                    <div
                        class="menu-item relative h-full bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-sm rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                                {{-- {{ $product->image ?? '' }}"  --}} alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                            </div>

                            @if ($product->is_featured)
                                <div
                                    class="featured-badge bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold absolute top-4 right-4 shadow-lg">
                                    <span class="material-icons text-sm">local_fire_department</span> SPECIAL
                                </div>
                            @endif

                            <div class="absolute bottom-4 left-4">
                                <span class="bg-black/70 text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ $product->category->name ?? 'Menu Item' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold text-white">{{ $product->name }}</h3>
                                @if ($currentDisplayMode['config']['showPrices'])
                                    <div
                                        class="price-tag bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($product->price, 2) }} DH
                                    </div>
                                @endif
                            </div>

                            @if ($currentDisplayMode['config']['showDescription'] && $product->description)
                                <p class="text-gray-300 text-sm mt-2 line-clamp-2">{{ $product->description }}</p>
                            @endif

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex -space-x-2">
                                        @if (isset($product->tags) && is_array($product->tags))
                                            @foreach (array_slice($product->tags, 0, 3) as $tag)
                                                <span
                                                    class="bg-gray-700 text-white text-xs px-2 py-1 rounded-full">{{ $tag }}</span>
                                            @endforeach
                                            @if (count($product->tags) > 3)
                                                <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-full">
                                                    +{{ count($product->tags) - 3 }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <button class="text-orange-400 hover:text-orange-300 transition-colors">
                                    <span class="material-icons">add_circle</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- SHOWCASE VIEW -->
            <div x-show="$wire.displayMode === 'SHOWCASE'" 
                 x-transition:enter="menu-transition fade-enter" 
                 x-transition:enter-start="fade-enter" 
                 x-transition:enter-end="fade-enter-active" 
                 x-transition:leave="menu-transition fade-leave" 
                 x-transition:leave-start="fade-leave" 
                 x-transition:leave-end="fade-leave-active" 
                 class="w-full h-full flex items-center justify-center p-4 ">
                <div x-data="{ product: getProductsForCurrentView()[activeIndex] }"
                    class="relative w-full max-w-screen mx-10 transition-all duration-500 ease-in-out bg-white rounded-xl shadow-lg border-2 border-red-500 overflow-hidden flex flex-col md:flex-row">
                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                        {{-- getProductsForCurrentView()[activeIndex].image || --}} {{-- 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200'" --}} :alt="getProductsForCurrentView()[activeIndex].name"
                        class="w-full md:w-3/4 h-auto md:h-[60vh] object-cover transition-all duration-500">

                    <template x-if="getProductsForCurrentView()[activeIndex].is_featured">
                        <div
                            class="bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg text-sm font-bold absolute top-4 right-4 shadow-lg flex items-center gap-1">
                            <span class="material-icons text-sm">star</span>
                            POPULAR
                        </div>
                    </template>

                    <div class="p-8 md:p-12 space-y-6 flex flex-col flex-grow">
                        <h3 class="text-gray-900 font-bold text-4xl md:text-6xl xl:text-8xl"
                            x-text="getProductsForCurrentView()[activeIndex].name"></h3>

                        <template x-if="$wire.currentDisplayMode.config.showDescription">
                            <p class="text-gray-600 text-xl md:text-2xl leading-relaxed flex-grow"
                                x-text="getProductsForCurrentView()[activeIndex].description"></p>
                        </template>
                        <template x-if="!$wire.currentDisplayMode.config.showDescription">
                            <div class="flex-grow"></div>
                        </template>

                        <template x-if="$wire.currentDisplayMode.config.showPrices">
                            <div class="text-red-600 font-black text-4xl md:text-6xl text-right">
                                <span
                                    x-text="parseFloat(getProductsForCurrentView()[activeIndex].price).toFixed(2)"></span>
                                DH
                            </div>
                        </template>

                        <div class="flex justify-end space-x-4 mt-4">
                            <button @click="prevItem()"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 p-3 rounded-full transition-all duration-300">
                                <span class="material-icons">chevron_left</span>
                            </button>
                            <button @click="nextItem()"
                                class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full transition-all duration-300">
                                <span class="material-icons">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- LIST VIEW (Text-Only, Fast Food Style) -->
            <div x-show="$wire.displayMode === 'LIST_VIEW'"
                x-transition:enter="menu-transition fade-enter" 
                x-transition:enter-start="fade-enter" 
                x-transition:enter-end="fade-enter-active" 
                x-transition:leave="menu-transition fade-leave" 
                x-transition:leave-start="fade-leave" 
                x-transition:leave-end="fade-leave-active" 
                class="w-full max-w-3xl h-full flex flex-col p-4 md:p-6 overflow-y-auto scrollbar-thin scrollbar-thumb-orange-500 scrollbar-track-gray-200">

                <!-- Optional: Add Category Headers if your data supports it -->
                <!-- Example: <h2 class="text-2xl font-bold text-gray-700 mt-6 mb-2 sticky top-0 bg-gray-100 py-2 px-4 rounded-t-lg">Burgers</h2> -->

                <div class="bg-white rounded-lg shadow-md border border-gray-100 divide-y divide-gray-200">
                    {{-- Use 'all' products for List View --}}
                    @foreach ($organizedProducts['all'] ?? [] as $index => $product)
                        <div
                            class="flex justify-between items-baseline p-3 md:p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                            <!-- Product Name and Optional Description -->
                            <div class="flex-1 pr-4">
                                <h3 class="text-gray-800 font-semibold text-base md:text-lg leading-tight">
                                    {{ $product->name }}</h3>
                                {{-- Keep description optional based on config, but keep it short for list view --}}
                                @if ($currentDisplayMode['config']['showDescription'] && $product->description)
                                    <p class="text-gray-500 text-xs md:text-sm line-clamp-1 mt-1">
                                        {{ $product->description }}</p>
                                @endif
                            </div>

                            <!-- Price -->
                            @if ($currentDisplayMode['config']['showPrices'])
                                <div class="text-right flex-shrink-0 pl-4">
                                    <span class="text-gray-500 text-xs md:text-sm mr-1">Starts at</span>
                                    <span class="text-red-600 font-bold text-base md:text-lg">
                                        {{ number_format($product->price, 2) }} DH
                                    </span>
                                </div>
                            @endif
                            {{-- Removed Featured Badge for cleaner text list --}}
                        </div>
                    @endforeach
                </div>
                @if (empty($organizedProducts['all']))
                    <div class="text-center text-gray-500 mt-10">No menu items available.</div>
                @endif
            </div>

            <!-- FEATURED/DYNAMIC VIEW -->
            <div x-show="$wire.displayMode === 'DYNAMIC_VIEW' || $wire.displayMode === 'FEATURED_VIEW'"
                x-transition:enter="menu-transition fade-enter" 
                x-transition:enter-start="fade-enter" 
                x-transition:enter-end="fade-enter-active" 
                x-transition:leave="menu-transition fade-leave" 
                x-transition:leave-start="fade-leave" 
                x-transition:leave-end="fade-leave-active" 
                class="w-full h-full grid grid-cols-1 md:grid-cols-3 gap-4 p-4 md:p-8 overflow-y-auto">
                @php
                    $displayedProducts =
                        $displayMode === 'FEATURED_VIEW'
                            ? $organizedProducts['all']->filter(function ($product) {
                                return $product->is_featured;
                            })
                            : $organizedProducts['all'];
                @endphp

                @if ($displayMode === 'FEATURED_VIEW' && $displayedProducts->isEmpty())
                    <div class="col-span-full flex items-center justify-center h-full">
                        <div class="text-center p-8 bg-white rounded-xl shadow-lg border border-gray-100">
                            <span class="material-icons text-gray-400 text-6xl mb-4">info</span>
                            <h3 class="text-gray-800 font-bold text-2xl">No Featured Items</h3>
                            <p class="text-gray-500 mt-2">There are currently no featured items to display.</p>
                        </div>
                    </div>
                @else
                    @foreach ($displayedProducts as $product)
                        <div
                            class="relative h-full transition-all duration-500 ease-in-out transform hover:scale-[1.02] bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-52 md:h-64 object-cover transition-all duration-500">

                            @if ($product->is_featured)
                                <div
                                    class="bg-yellow-500 text-gray-900 px-3 py-1.5 rounded-full text-sm font-bold absolute top-4 right-4 shadow-lg flex items-center gap-1">
                                    <span class="material-icons text-sm">star</span>
                                    FEATURED
                                </div>
                            @endif

                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-gray-900 font-bold text-2xl md:text-3xl">{{ $product->name }}</h3>
                                @if ($currentDisplayMode['config']['showDescription'] && $product->description)
                                    <p class="text-gray-600 text-lg md:text-xl line-clamp-3 mt-2 flex-grow">
                                        {{ $product->description }}</p>
                                @else
                                    <div class="flex-grow"></div>
                                @endif
                                @if ($currentDisplayMode['config']['showPrices'])
                                    <div class="text-red-600 font-black text-2xl md:text-3xl mt-4 text-right">
                                        {{ number_format($product->price, 2) }} DH</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Display Mode Selector -->
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-20">
            <div class="flex items-center gap-2 bg-black/60 backdrop-blur-sm rounded-full p-2 shadow-xl">
                @foreach ($displayModes as $key => $mode)
                    <button wire:click="setDisplayMode('{{ $key }}')"
                        class="p-3 rounded-full transition-all duration-300 {{ $displayMode === $key ? 'bg-orange-500 text-white scale-110' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <span class="material-icons">
                            @if ($mode['icon'] === 'layout-grid')
                                grid_view
                            @elseif($mode['icon'] === 'maximize-2')
                                fullscreen
                            @elseif($mode['icon'] === 'rows')
                                view_list
                            @elseif($mode['icon'] === 'star')
                                star
                            @else
                                view_column
                            @endif
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Theme Selector -->
        <div class="absolute bottom-6 right-6 z-20">
            <div class="flex items-center gap-3 bg-black/60 backdrop-blur-sm rounded-full p-2">
                @foreach ($themes as $key => $theme)
                    <button wire:click="setTheme('{{ $key }}')"
                        class="p-3 rounded-full transition-all duration-300 {{ $themeConfig['name'] === $theme['name'] ? 'bg-orange-500 text-white scale-110' : 'text-orange-500 hover:orange-500 hover:bg-white/10' }}">
                        <span class="material-icons">
                            @if ($key === 'dark')
                                dark_mode
                            @elseif($key === 'light')
                                light_mode
                            @elseif($key === 'vintage')
                                wine_bar
                            @elseif($key === 'modern')
                                brush
                            @else
                                palette
                            @endif
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Fullscreen Button -->
        <button x-data
            @click="document.documentElement.requestFullscreen ? 
                    (document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()) : 
                    alert('Fullscreen not supported by your browser')"
            class="fixed bottom-6 left-6 z-20 bg-black/60 backdrop-blur-sm rounded-full p-3 text-white hover:bg-white/10 transition-all duration-300 shadow-lg">
            <span class="material-icons">fullscreen</span>
        </button>

        <!-- Settings Panel -->
        @if ($showSettings)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center px-4">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6 sticky top-0 bg-white py-2 z-10">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <span class="material-icons">settings</span>
                            Menu Display Settings
                        </h2>
                        <button wire:click="toggleSettings" class="text-gray-500 hover:text-gray-700 p-2">
                            <span class="sr-only">Close</span>
                            ×
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Theme Selection -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3">Theme</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($themes as $key => $theme)
                                    <button wire:click="setTheme('{{ $key }}')"
                                        class="p-3 rounded-lg transition-colors {{ $themeConfig['name'] === $theme['name'] ? 'ring-2 ring-orange-500' : '' }}"
                                        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['text'] }};">
                                        {{ $theme['name'] }}
                                        @if ($themeConfig['name'] === $theme['name'])
                                            <span class="material-icons ml-2">check</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Display Mode -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3">Display Mode</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($displayModes as $key => $mode)
                                    <button wire:click="setDisplayMode('{{ $key }}')"
                                        class="flex items-center justify-between p-3 rounded-lg transition-colors {{ $displayMode === $key ? 'bg-orange-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                                        <span>{{ $mode['label'] }}</span>
                                        @if ($displayMode === $key)
                                            <span class="material-icons">check</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Auto Rotation -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium">Auto Rotate</h3>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="autoRotate" x-on:change="setupAutoRotate()"
                                        class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                    </div>
                                </label>
                            </div>

                            <!-- Rotation Interval (only shown when auto-rotate is enabled) -->
                            <div x-show="autoRotate" class="mt-3">
                                <label class="text-sm text-gray-600 mb-1 block">Rotation Interval (seconds)</label>
                                <input type="range" x-model.number="rotationInterval" min="3000"
                                    max="30000" step="1000" x-on:change="setupAutoRotate()"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>3s</span>
                                    <span x-text="(rotationInterval/1000) + 's'"></span>
                                    <span>30s</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3">Display Options</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Prices</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="currentDisplayMode.config.showPrices"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                        </div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Description</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                            wire:model.live="currentDisplayMode.config.showDescription"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                        </div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Clock</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="uiConfig.showClock"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                        </div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Temperature</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="uiConfig.showTemperature"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Add the styles needed for the new design (animations, price tag shine, etc.) --}}
    @push('styles')
        <style>
            /* Make sure Font Awesome is loaded (can be done via CDN link in main layout or here) */
            /* @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'); */

            /* Base theme variables (driven by Livewire/Alpine applyThemeVariables) */
            :root {
                --primary-color: {{ $themeConfig['primary'] ?? '#F97316' }};
                --secondary-color: {{ $themeConfig['secondary'] ?? '#EA580C' }};
                --background-color: {{ $themeConfig['background'] ?? '#111827' }};
                --text-color: {{ $themeConfig['text'] ?? '#FFFFFF' }};
                --accent-color: {{ $themeConfig['accent'] ?? '#FDBA74' }};
            }

            /* Animations from target design */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                opacity: 0;
                /* Start hidden */
                animation: fadeIn 0.5s ease-out forwards;
            }

            .price-tag {
                position: relative;
                overflow: hidden;
            }

            .price-tag::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(45deg, transparent 25%, rgba(255, 255, 255, 0.1) 50%, transparent 75%);
                background-size: 400% 400%;
                animation: shine 3s infinite;
            }

            @keyframes shine {
                0% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            .featured-badge {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.7);
                }

                /* Use accent or primary color */
                70% {
                    box-shadow: 0 0 0 10px rgba(234, 88, 12, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(234, 88, 12, 0);
                }
            }

            /* Menu item hover effect from target design */
            .menu-item {
                transition: all 0.3s ease;
                transform-origin: center;
            }

            .menu-item:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }

            /* Transition classes from target design for Alpine x-transition */
            .menu-transition {
                transition: opacity 0.5s ease, transform 0.5s ease;
            }

            .fade-enter {
                opacity: 0;
                transform: scale(0.95);
            }

            .fade-enter-active {
                opacity: 1;
                transform: scale(1);
            }

            .fade-leave {
                opacity: 1;
                transform: scale(1);
            }

            .fade-leave-active {
                opacity: 0;
                transform: scale(0.95);
            }

            [x-cloak] {
                display: none !important;
            }

            /* Custom Scrollbar (optional but nice) */
            .scrollbar-thin {
                scrollbar-width: thin;
                scrollbar-color: var(--accent-color) var(--background-color);
            }

            .scrollbar-thin::-webkit-scrollbar {
                width: 8px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: var(--background-color);
                border-radius: 20px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background-color: var(--accent-color);
                border-radius: 20px;
                border: 2px solid var(--background-color);
            }

            /* Ensure icons align nicely in buttons */
            button i.fas {
                line-height: 1;
                vertical-align: middle;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Add any view-specific JS logic here if needed,
            // but most interaction relies on Alpine defined in the main x-data.
            document.addEventListener('livewire:navigated', () => {
                // Re-initialize Alpine components if needed after navigation
                // Usually handled by Livewire automatically, but good to keep in mind.
                if (typeof Alpine !== 'undefined') {
                    Alpine.start();
                }

                // Re-apply theme in case navigation resets styles (less common now but safe)
                const rootData = document.querySelector('[x-data]').__x;
                if (rootData && typeof rootData.applyThemeVariables === 'function') {
                    rootData.applyThemeVariables();
                }
            });
        </script>
    @endpush

    <style>
        :root {
            --primary-color: {{ $themeConfig['primary'] }};
            --secondary-color: {{ $themeConfig['secondary'] }};
            --background-color: {{ $themeConfig['background'] }};
            --text-color: {{ $themeConfig['text'] }};
            --accent-color: {{ $themeConfig['accent'] }};
        }

        [x-cloak] {
            display: none !important;
        }

        .transition-fade {
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
    </style>
</div>
