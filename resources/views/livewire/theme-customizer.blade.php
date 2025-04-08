<div>
    <!-- Toggle Button -->
    <button
        @click="$wire.isOpen = !$wire.isOpen"
        class="fixed bottom-4 right-4 z-40 p-3 rounded-full shadow-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        aria-label="Toggle theme customizer"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </button>

    <!-- Theme Customizer Panel -->
    <div
        x-show="$wire.isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4"
        class="fixed bottom-20 right-4 z-50 w-80 rounded-lg shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden"
    >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                <span class="font-medium text-gray-900 dark:text-white">Theme Customizer</span>
            </div>
            <button
                @click="$wire.isOpen = false"
                class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                aria-label="Close theme customizer"
            >
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-4 max-h-[calc(100vh-200px)] overflow-y-auto space-y-4">
            <!-- Appearance Section -->
            <div class="space-y-4">
                <button
                    @click="$wire.toggleSection('appearance')"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Appearance</span>
                    </div>
                    <svg
                        class="w-4 h-4 text-gray-500 dark:text-gray-400 transform transition-transform"
                        :class="{ 'rotate-180': $wire.sections.appearance }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="$wire.sections.appearance" class="space-y-4 pl-6">
                    <!-- Light/Dark Mode -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mode</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                @click="$wire.setAppearance('light')"
                                class="flex-1 py-2 px-3 rounded-md flex items-center justify-center gap-2 transition-colors"
                                :class="$wire.theme.appearance === 'light' ? 'bg-accent-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Light</span>
                            </button>
                            <button
                                @click="$wire.setAppearance('dark')"
                                class="flex-1 py-2 px-3 rounded-md flex items-center justify-center gap-2 transition-colors"
                                :class="$wire.theme.appearance === 'dark' ? 'bg-accent-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <span>Dark</span>
                            </button>
                        </div>
                    </div>

                    <!-- TV Mode -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">TV Mode</label>
                            <button
                                @click="$wire.setTVMode(!$wire.theme.tvMode)"
                                class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                                :class="$wire.theme.tvMode ? 'bg-accent-600' : 'bg-gray-200 dark:bg-gray-700'"
                            >
                                <span
                                    class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="$wire.theme.tvMode ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Optimizes UI for display on large screens
                        </p>
                    </div>
                </div>
            </div>

            <!-- Colors Section -->
            <div class="space-y-4">
                <button
                    @click="$wire.toggleSection('colors')"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Colors</span>
                    </div>
                    <svg
                        class="w-4 h-4 text-gray-500 dark:text-gray-400 transform transition-transform"
                        :class="{ 'rotate-180': $wire.sections.colors }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="$wire.sections.colors" class="space-y-4 pl-6">
                    <!-- Accent Colors -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Accent Color</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach(['orange', 'blue', 'green', 'purple', 'crimson'] as $color)
                                <button
                                    @click="$wire.setAccentColor('{{ $color }}')"
                                    class="w-8 h-8 rounded-full transition-all border-2"
                                    :class="$wire.theme.accentColor === '{{ $color }}' ? 'border-current scale-110 ring-2 ring-offset-2' : 'border-transparent hover:scale-105'"
                                    style="background-color: var(--{{ $color }}-600)"
                                    aria-label="Select {{ $color }} color"
                                ></button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Gray Scale -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Gray Scale</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach(['mauve', 'slate', 'sage', 'olive', 'sand'] as $color)
                                <button
                                    @click="$wire.setGrayColor('{{ $color }}')"
                                    class="w-8 h-8 rounded-full transition-all border-2"
                                    :class="$wire.theme.grayColor === '{{ $color }}' ? 'border-current scale-110 ring-2 ring-offset-2' : 'border-transparent hover:scale-105'"
                                    style="background-color: var(--{{ $color }}-600)"
                                    aria-label="Select {{ $color }} color"
                                ></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography & Layout Section -->
            <div class="space-y-4">
                <button
                    @click="$wire.toggleSection('typography')"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Typography & Layout</span>
                    </div>
                    <svg
                        class="w-4 h-4 text-gray-500 dark:text-gray-400 transform transition-transform"
                        :class="{ 'rotate-180': $wire.sections.typography }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="$wire.sections.typography" class="space-y-4 pl-6">
                    <!-- UI Scaling -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">UI Scaling</label>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $theme['scaling'] }}</span>
                        </div>
                        <input
                            type="range"
                            min="90"
                            max="110"
                            step="5"
                            wire:model.live="theme.scaling"
                            class="w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-accent-600"
                        >
                    </div>

                    <!-- Border Radius -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Border Radius</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach(['none', 'small', 'medium', 'large', 'full'] as $radius)
                                <button
                                    @click="$wire.setRadius('{{ $radius }}')"
                                    class="flex-1 py-1 px-2 text-sm border transition-colors"
                                    :class="$wire.theme.radius === '{{ $radius }}' ? 'bg-accent-600 text-white border-accent-600' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'"
                                    :class="{
                                        'rounded-none': $radius === 'none',
                                        'rounded-sm': $radius === 'small',
                                        'rounded-md': $radius === 'medium',
                                        'rounded-lg': $radius === 'large',
                                        'rounded-full': $radius === 'full'
                                    }"
                                >
                                    {{ ucfirst($radius) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 