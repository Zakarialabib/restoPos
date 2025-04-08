<x-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-2 px-4 py-4">
    {{-- Dashboards Section --}}
    <x-sidebar.dropdown title="{{ __('Dashboards') }}" :active="request()->routeIs(['admin.dashboard', 'admin.kitchen'])">
        <x-slot name="icon">
            <span class="material-icons text-2xl text-orange-200">dashboard</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Overview') }}" href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" />
        <x-sidebar.sublink title="{{ __('Kitchen') }}" href="{{ route('admin.kitchen') }}" :active="request()->routeIs('admin.kitchen')" />
        <x-sidebar.sublink title="{{ __('Kitchen dash') }}" href="{{ route('admin.kitchen.dashboard') }}"
            :active="request()->routeIs('admin.kitchen.dashboard')" />
    </x-sidebar.dropdown>

    {{-- Products & Inventory Section --}}
    <x-sidebar.dropdown title="{{ __('Inventory') }}" :active="request()->routeIs([
        'admin.products',
        'admin.categories',
        'admin.inventory.ingredients',
        'admin.inventory.recipes',
        'admin.inventory',
        'admin.finance.purchases',
        'admin.products.composable',
    ])">
        <x-slot name="icon">
            <span class="material-icons text-2xl text-orange-200">inventory</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Products') }}" href="{{ route('admin.products') }}" :active="request()->routeIs('admin.products')" />
        <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('admin.categories') }}" :active="request()->routeIs('admin.categories')" />
        <x-sidebar.sublink title="{{ __('Recipes') }}" href="{{ route('admin.inventory.recipes') }}" :active="request()->routeIs('admin.inventory.recipes')" />
        <x-sidebar.sublink title="{{ __('Ingredients') }}" href="{{ route('admin.inventory.ingredients') }}"
            :active="request()->routeIs('admin.inventory.ingredients')" />
        <x-sidebar.sublink title="{{ __('Inventory') }}" href="{{ route('admin.inventory') }}" :active="request()->routeIs('admin.inventory')" />
    </x-sidebar.dropdown>

    {{-- Operations Section --}}
    <x-sidebar.dropdown title="{{ __('Operations') }}" :active="request()->routeIs([
        'admin.orders',
        'admin.finance.cash-register',
        'admin.finance.expenses',
        'admin.finance.expense-categories',
        'admin.inventory.waste',
        'admin.finance.purchases',
        'admin.suppliers',
    ])">
        <x-slot name="icon">
            <span class="material-icons text-2xl text-orange-200">shopping_cart</span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Orders') }}" href="{{ route('admin.orders') }}" :active="request()->routeIs('admin.orders')" />
        <x-sidebar.sublink title="{{ __('Cash Register') }}" href="{{ route('admin.finance.cash-register') }}"
            :active="request()->routeIs('admin.finance.cash-register')" />
        <x-sidebar.sublink title="{{ __('Expenses') }}" href="{{ route('admin.finance.expenses') }}" :active="request()->routeIs('admin.finance.expenses')" />
        <x-sidebar.sublink title="{{ __('Expense Categories') }}" href="{{ route('admin.finance.expense-categories') }}"
            :active="request()->routeIs('admin.finance.expense-categories')" />
        <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('admin.suppliers') }}" :active="request()->routeIs('admin.suppliers')" />
        <x-sidebar.sublink title="{{ __('Purchase') }}" href="{{ route('admin.finance.purchases') }}" :active="request()->routeIs('admin.finance.purchases')" />
        <x-sidebar.sublink title="{{ __('Waste Management') }}" href="{{ route('admin.inventory.waste') }}"
            :active="request()->routeIs('admin.inventory.waste')" />
    </x-sidebar.dropdown>
    <x-sidebar.dropdown title="{{ __('Settings') }}" :active="request()->routeIs(['admin.settings', 'admin.settings.languages'])">
        <x-slot name="icon">
            <span class="material-icons text-2xl text-orange-200">settings</span>
        </x-slot>

        <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('admin.settings') }}"
            :active="request()->routeIs('admin.settings')" />
        <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('admin.settings.languages') }}"
            :active="request()->routeIs('admin.settings.languages')" />

    </x-sidebar.dropdown>
    {{-- Profile Link --}}
    <x-sidebar.link title="{{ __('Profile') }}" href="{{ route('profile') }}" :isActive="request()->routeIs('profile')">
        <x-slot name="icon">
            <span class="material-icons text-2xl text-orange-200">person</span>
        </x-slot>
    </x-sidebar.link>
</x-scrollbar>
