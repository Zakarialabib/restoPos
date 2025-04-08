<header
    class="bg-gradient-to-r from-orange-600 to-orange-500 text-white px-4 py-2 flex justify-between items-center shadow-md z-10"
    x-data="{ isSidebarOpen: false, isUserMenuOpen: false }">
    <!-- Logo Section -->
    <a href="{{ route('index') }}" class="flex items-center space-x-2 no-underline text-white">
        {{-- <img class="w-12 h-auto rounded-full border-2 border-yellow-600 shadow-md hover:scale-105 transition-transform duration-300"
            src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name') }}"
            onerror="this.onerror=null;this.src='{{ asset('images/logo/logo.png') }}';" loading="lazy"> --}}
        <span class="text-2xl font-bold">{{ config('app.name') }}</span>
    </a>

    <!-- Navigation & User Menu -->
    <div class="flex items-center space-x-4 text-base font-medium">
        <div class="hidden lg:flex space-x-6">
            <a href="{{ url('compose/fruits') }}" class="text-white hover:text-yellow-200 transition duration-200">Compose
                Juice</a>

            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-white hover:text-yellow-200 transition duration-200">Admin Dashboard</a>
                @endif

                @if (auth()->user()->hasRole(['admin', 'manager']))
                    <a href="{{ route('pos') }}" class="text-white hover:text-yellow-200 transition duration-200">POS
                        System</a>
                @endif
            @endauth
        </div>

        <!-- Authentication Menu -->
        <div class="relative">
            @auth
                <div class="flex items-center">
                    <span
                        class="inline-flex items-center px-2 py-1 mr-2 text-xs font-medium rounded-full bg-green-600 text-white">
                        <span class="w-2 h-2 mr-1 rounded-full bg-white"></span>Online
                    </span>

                    <button @click="isUserMenuOpen = !isUserMenuOpen"
                        class="flex items-center gap-2 text-base font-medium hover:text-yellow-200 transition duration-200">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300"
                            :class="{ 'rotate-180': isUserMenuOpen }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="isUserMenuOpen" @click.away="isUserMenuOpen = false" x-transition
                        class="absolute right-0 mt-2 w-48 py-2 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 text-sm hover:bg-yellow-100">Admin Dashboard</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 text-sm hover:bg-yellow-100">Dashboard</a>
                        @endif

                        @if (auth()->user()->hasRole(['admin', 'manager']))
                            <a href="{{ route('pos') }}" class="block px-4 py-2 text-sm hover:bg-yellow-100">POS
                                System</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-yellow-100">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}"
                        class="text-white hover:text-yellow-200 transition duration-200">Login</a>
                    <a href="{{ route('register') }}"
                        class="text-white hover:text-yellow-200 transition duration-200">Register</a>
                </div>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button @click="isSidebarOpen = !isSidebarOpen"
            class="lg:hidden text-white hover:text-yellow-200 transition duration-200">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Mobile Sidebar -->
    <div class="fixed inset-0 bg-black bg-opacity-75 z-40" x-show="isSidebarOpen" x-transition
        @click.away="isSidebarOpen = false" x-cloak>
        <div class="fixed top-0 right-0 w-3/4 max-w-sm h-full bg-yellow-600 p-6 text-white">
            <button @click="isSidebarOpen = false" class="text-white absolute top-4 right-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <nav class="flex flex-col items-start space-y-4 mt-10">
                <a href="{{ url('compose/fruits') }}" class="text-base text-white hover:text-yellow-200">Compose
                    Juice</a>

                @auth
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-base text-white hover:text-yellow-200">Admin
                            Dashboard</a>
                    @endif

                    @if (auth()->user()->hasRole(['admin', 'manager']))
                        <a href="{{ route('pos') }}" class="text-base text-white hover:text-yellow-200">POS System</a>
                    @endif

                    <div class="pt-4 border-t border-yellow-500 w-full">
                        <div class="flex items-center mb-4">
                            <span
                                class="inline-flex items-center px-2 py-1 mr-2 text-xs font-medium rounded-full bg-green-600 text-white">
                                <span class="w-2 h-2 mr-1 rounded-full bg-white"></span>Online
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-base text-white hover:text-yellow-200">
                                Log Out
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-4 border-t border-yellow-500 w-full">
                        <a href="{{ route('login') }}"
                            class="block py-2 text-base text-white hover:text-yellow-200">Login</a>
                        <a href="{{ route('register') }}"
                            class="block py-2 text-base text-white hover:text-yellow-200">Register</a>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</header>
