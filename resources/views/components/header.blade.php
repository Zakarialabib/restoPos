<div class="w-full z-40 sticky top-0 bg-retro-orange" x-data="{ isSidebar: false }">
    <nav class="flex items-center justify-between px-3 py-4">
        <a href="{{ route('index') }}" class="text-xl font-semibold dark:text-gray-100">
            <img class="w-24 h-auto" src="" alt="{{ config('app.name') }}"
                onerror="this.onerror=null;this.src='{{ asset('images/logo/logo.png') }}';" loading="lazy">
            <span class="sr-only">
                {{ config('app.name') }}
            </span>
        </a>
        <div class="flex flex-wrap items-center justify-center gap-x-8">
            <div class="hidden md:flex gap-4">
                <x-nav-link :href="route('compose.juices')" :active="request()->routeIs('compose.juices')">
                    {{ __('Composable Juices') }}
                </x-nav-link>
                {{-- composable coffees --}}
                <x-nav-link :href="route('compose.coffees')" :active="request()->routeIs('compose.coffees')">
                    {{ __('Composable Coffee') }}
                </x-nav-link>
                {{-- composable dried fruits --}}
                <x-nav-link :href="route('compose.dried-fruits')" :active="request()->routeIs('compose.dried-fruits')">
                    {{ __('Composable Dried Fruits') }}
                </x-nav-link>
                <livewire:lang-switcher />
            </div>
            <div class="hidden md:flex gap-4">
                @if (Auth::check())
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <i class="fa fa-caret-down"></i> {{ Auth::user()->name }}
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link href="{{ route('admin.dashboard') }}">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>

                            <div class="border-t border-green-800"></div>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start" type="button">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <div
                                class="flex items-center text-black hover:text-green-400 gap-x-2 leading-10 after:absolute after:bottom-[10px] after:left-0 after:bg-white after:transition-transform after:w-full after:origin-left after:scale-x-100 pr-4">
                                <svg class="ml-2 h-6 w-6 fill-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 6C10.343 6 9 7.343 9 9C9 10.657 10.343 12 12 12C13.656 12 15 10.657 15 9C15 7.343 13.656 6 12 6ZM12 10C11.447 10 11 9.553 11 9C11 8.448 11.447 8 12 8C12.553 8 13 8.448 13 9C13 9.553 12.553 10 12 10ZM12 0C5.372 0 0 5.372 0 12C0 18.627 5.372 24 12 24C18.627 24 24 18.627 24 12C24 5.372 18.627 0 12 0ZM12 22C6.478 22 2 17.523 2 12C2 6.478 6.478 2 12 2C17.522 2 22 6.478 22 12C22 17.523 17.522 22 12 22Z">
                                    </path>
                                    <path
                                        d="M11 16H13C14.103 16 15 16.896 15 18H17C17 15.791 15.209 14 13 14H11C8.791 14 7 15.791 7 18H9C9 16.896 9.897 16 11 16Z">
                                    </path>
                                </svg>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            {{-- route with anchor login  --}}
                            <x-dropdown-link href="{{ route('login') }}">
                                {{ __('Login') }}
                            </x-dropdown-link>
                            {{-- route with anchor --}}
                            <x-dropdown-link href="{{ route('register') }}">
                                {{ __('Register') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>
            <button type="button" class="md:hidden" @click="isSidebar = !isSidebar"
                class="bg-transparent p-2 inline-flex items-center justify-center text-green-600 dark:text-blue-600 hover:text-green-500 dark:hover:text-blue-500">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': isSidebar, 'inline-flex': !isSidebar }" class="inline-flex"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !isSidebar, 'inline-flex': isSidebar }" class="hidden"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>


    <div class="fixed top-0 left-0 bottom-0 w-5/6 max-w-sm z-50 overflow-y-scroll" x-show="isSidebar"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        @click.away="isSidebar = false" x-cloak>
        <div class="fixed inset-0 bg-gray-800 opacity-25 transition-opacity"
            x-transition:enter="transition ease-out duration-100" x-transition:leave="transition ease-in duration-100"
            x-on:click="isSidebar = false"></div>
        {{-- <div class="fixed inset-0 bg-gray-800 opacity-25"></div> --}}
        <nav class="relative flex flex-col py-6 px-6 w-full h-full bg-white border-r overflow-y-scroll">
            <div class="flex items-center">
                <a class="mr-auto lg:text-3xl sm:text-xl font-bold font-heading" href="{{ route('index') }}">
                    <img class="w-auto h-14" src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name') }}"
                        loading="lazy" />
                </a>
                <button @click="isSidebar = false" type="button">
                    <svg class="h-5 w-5 text-gray-500 cursor-pointer" width="10" height="10" viewbox="0 0 10 10"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.00002 1L1 9.00002M1.00003 1L9.00005 9.00002" stroke="black" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <div class="border-t border-gray-900 py-2"></div>

            <a class="mb-2 text-base font-semibold text-gray-900  hover:text-blue-600 hover:underline "
                href="{{ route('compose.juices') }}">
                {{ __('Composable Juices') }}
            </a>
            <div class="border-t border-gray-900 py-2"></div>

            <a class="mb-2 text-base font-semibold text-gray-900  hover:text-blue-600 hover:underline "
                href="route('compose.coffees')">
                {{ __('Composable Coffee') }}

            </a>
            <div class="border-t border-gray-900 py-2"></div>

            <a class="mb-2 text-base font-semibold text-gray-900  hover:text-blue-600 hover:underline "
                href="route('compose.dried-fruits')">
                {{ __('Composable Dried Fruits') }}
            </a>

            <div class="border-t border-gray-900 py-2"></div>

            <div class="flex justify-between">
                @if (Auth::check())
                    <div class="w-full lg:text-3xl sm:text-xl font-bold font-heading">
                        <div class="py-3">
                            <a href="#" class="hover:text-green-500">
                                {{ Auth::user()->name }}
                            </a>
                        </div>
                        {{-- check user if auth only  --}}
                        <div class="py-3">
                            <a class="hover:text-green-500" href="{{ route('admin.dashboard') }}">
                                {{ __('Dashboard') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-900 py-2"></div>
                    <div class="w-full lg:text-3xl sm:text-xl font-bold font-heading">
                        <div class="py-3">
                            <a class="hover:text-green-500" href="{{ route('login') }}"
                                x-on:click.prevent="isTab = 'login'">{{ __('Login') }}
                            </a>
                        </div>
                        {{ __('or') }}
                        <div class="py-3">
                            <a class="hover:text-green-500" href="{{ route('register') }}"
                                x-on:click.prevent="isTab = 'register'">
                                {{ __('Register') }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </nav>
    </div>
</div>
