<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('logo.png') }}" alt="Logo">
                </div>

                {{-- Main Navigation --}}
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.ingredients') }}" :active="request()->routeIs('admin.ingredients.*')">
                        Ingredients
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.products') }}" :active="request()->routeIs('admin.products.*')">
                        Products
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.recipes') }}" :active="request()->routeIs('admin.recipes.*')">
                        Recipes
                    </x-nav-link>
                </div>
            </div>

            {{-- User Menu --}}
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <x-dropdown>
                    <x-slot name="trigger">
                        <button
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                            <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}">
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('profile') }}">
                            Profile
                        </x-dropdown-link>
                        <x-dropdown-link href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
