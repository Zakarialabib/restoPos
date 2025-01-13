<x-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-4">
    {{-- Dashboards Section --}}
    <x-sidebar.dropdown title="{{ __('Dashboards') }}" :active="request()->routeIs(['admin.dashboard'])">
        <x-slot name="icon">
            <span class="material-icons text-2xl">dashboard</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Overview') }}" href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" />
    </x-sidebar.dropdown>

    {{-- Products Section --}}
    <x-sidebar.dropdown title="{{ __('Products') }}" :active="request()->routeIs(['admin.products', 'admin.categories', 'admin.ingredients', 'admin.recipes'])">
        <x-slot name="icon">
            <span class="material-icons text-2xl">inventory</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Products') }}" href="{{ route('admin.products') }}" :active="request()->routeIs('admin.products')" />
        <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('admin.categories') }}" :active="request()->routeIs('admin.categories')" />
        <x-sidebar.sublink title="{{ __('Ingredients') }}" href="{{ route('admin.ingredients') }}" :active="request()->routeIs('admin.ingredients')" />
        <x-sidebar.sublink title="{{ __('Recipes') }}" href="{{ route('admin.recipes') }}" :active="request()->routeIs('admin.recipes')" />
    </x-sidebar.dropdown>

    {{-- Operations Section --}}
    <x-sidebar.dropdown title="{{ __('Operations') }}" :active="request()->routeIs(['admin.orders', 'admin.cash-register', 'admin.expense', 'admin.expense-categories'])">
        <x-slot name="icon">
            <span class="material-icons text-2xl">shopping_cart</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Orders') }}" href="{{ route('admin.orders') }}" :active="request()->routeIs('admin.orders')" />
        <x-sidebar.sublink title="{{ __('Cash Register') }}" href="{{ route('admin.cash-register') }}" :active="request()->routeIs('admin.cash-register')" />
        <x-sidebar.sublink title="{{ __('Expense') }}" href="{{ route('admin.expense') }}" :active="request()->routeIs('admin.expense')" />
        <x-sidebar.sublink title="{{ __('Expense Categories') }}" href="{{ route('admin.expense-categories') }}" :active="request()->routeIs('admin.expense-categories')" />
    </x-sidebar.dropdown>

    {{-- Profile Link --}}
    <x-sidebar.link title="{{ __('Profile') }}" href="{{ route('profile') }}" :isActive="request()->routeIs('profile')">
        <x-slot name="icon">
            <span class="material-icons text-2xl">person</span>
        </x-slot>
    </x-sidebar.link>
</x-scrollbar>
