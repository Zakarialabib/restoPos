<x-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-3 px-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('admin.dashboard') }}" :isActive="request()->routeIs('admin.dashboard')">
        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <x-icons.dashboard class="w-5 h-5" aria-hidden="true" />
            </span>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="{{ __('Products') }}" :active="request()->routeIs(['admin.products'])">

        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <span class="material-icons">
                    inventory
                </span>
            </span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Products') }}" href="{{ route('admin.products') }}" :active="request()->routeIs('admin.products')" />
        <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('admin.categories') }}" :active="request()->routeIs('admin.categories')" />
        <x-sidebar.sublink title="{{ __('Ingredients') }}" href="{{ route('admin.ingredients') }}" :active="request()->routeIs('admin.ingredients')" />
        <x-sidebar.sublink title="{{ __('Recipes') }}" href="{{ route('admin.recipes') }}" :active="request()->routeIs('admin.recipes')" />
    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="{{ __('Transactions') }}" :active="request()->routeIs(['admin.orders'])">
        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <i class="fas fa-shopping-cart w-5 h-5"></i>
            </span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Orders list') }}" href="{{ route('admin.orders') }}" :active="request()->routeIs('admin.orders')" />
    </x-sidebar.dropdown>

    {{-- <x-sidebar.dropdown title="{{ __('Reports') }}" :active="request()->routeIs([
        'admin.purchases-report.index',
        'admin.sales-report.index',
        'admin.sales-return-report.index',
        'admin.payments-report.index',
        'admin.purchases-return-report.index',
        'admin.profit-loss-report.index',
    ])">
        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <i class="fas fa-chart-line w-5 h-5"></i>
            </span>
        </x-slot>

        <x-sidebar.sublink title="{{ __('Customer Report') }}" href="{{ route('admin.customers-report.index') }}"
            :active="request()->routeIs('admin.customers-report.index')" />
        <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('admin.profit-loss-report.index') }}"
            :active="request()->routeIs('admin.profit-loss-report.index')" />
        <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('admin.profit-loss-report.index') }}"
            :active="request()->routeIs('admin.profit-loss-report.index')" />
        <x-sidebar.sublink title="{{ __('Purchases Report') }}" href="{{ route('admin.purchases-report.index') }}"
            :active="request()->routeIs('admin.purchases-report.index')" />
        <x-sidebar.sublink title="{{ __('Purchases Return Report') }}"
            href="{{ route('admin.purchases-return-report.index') }}" :active="request()->routeIs('admin.purchases-return-report.index')" />
        <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('admin.sales-report.index') }}"
            :active="request()->routeIs('admin.sales-report.index')" />
        <x-sidebar.sublink title="{{ __('Sale Return Report') }}"
            href="{{ route('admin.sales-return-report.index') }}" :active="request()->routeIs('admin.sales-return-report.index')" />
        <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('admin.payments-report.index') }}"
            :active="request()->routeIs('admin.payments-report.index')" />

    </x-sidebar.dropdown> --}}

    {{-- <x-sidebar.dropdown title="{{ __('Settings') }}" :active="request()->routeIs([
        'admin.settings.index',
        'admin.logs.index',
        'admin.currencies.index',
        'admin.languages.index',
        'setting.backup',
    ])">
        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <i class="fas fa-cog w-5 h-5"></i>
            </span>
        </x-slot>
        <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('admin.settings.index') }}"
            :active="request()->routeIs('admin.settings.index')" />
        <x-sidebar.sublink title="{{ __('Menu Settings') }}" href="{{ route('admin.menu-settings.index') }}"
            :active="request()->routeIs('admin.menu-settings.index')" />

        <x-sidebar.sublink title="{{ __('Logs') }}" href="{{ route('admin.logs.index') }}" :active="request()->routeIs('admin.logs.index')" />
        <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('admin.currencies.index') }}"
            :active="request()->routeIs('admin.currencies.index')" />
        <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('admin.languages.index') }}"
            :active="request()->routeIs('admin.languages.index')" />
        <x-sidebar.sublink title="{{ __('Backup') }}" href="{{ route('admin.setting.backup') }}"
            :active="request()->routeIs('admin.setting.backup')" />

        <x-sidebar.sublink title="{{ __('Shipping') }}" href="{{ route('admin.shipping.index') }}"
            :active="request()->routeIs('admin.shipping.index')" />
        <x-sidebar.sublink title="{{ __('Redirects') }}" href="{{ route('admin.setting.redirects') }}"
            :active="request()->routeIs('admin.setting.redirects')" />

    </x-sidebar.dropdown> --}}

    {{-- <x-sidebar.link title="{{ __('Logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logoutform').submit();"
        href="#">
        <x-slot name="icon">
            <span class="inline-block ltr:mx-3 rtl:mr-3">
                <i class="fas fa-sign-out-alt w-5 h-5" aria-hidden="true"></i>
            </span>
        </x-slot>
    </x-sidebar.link> --}}

</x-scrollbar>
