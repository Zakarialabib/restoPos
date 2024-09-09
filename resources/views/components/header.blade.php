<div class="w-full z-40 sticky top-0 bg-retro-orange">
    <header class="max-w-7xl mx-auto  text-white text-center py-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold">
            <a href="{{ route('index') }}" class="no-underline text-white" wire:navigate>
                {{ config('app.name') }}
            </a>
        </h1>

        <div class="flex gap-4">
            {{-- composable juices --}}
            <x-nav-link :href="route('compose.juices')" :active="request()->routeIs('compose.juices')" wire:navigate>
                {{ __('Composable Juices') }}
            </x-nav-link>
            {{-- composable coffees --}}
            <x-nav-link :href="route('compose.coffees')" :active="request()->routeIs('compose.coffees')" wire:navigate>
                {{ __('Composable Coffee') }}
            </x-nav-link>
            {{-- composable dried fruits --}}
            <x-nav-link :href="route('compose.dried-fruits')" :active="request()->routeIs('compose.dried-fruits')" wire:navigate>
                {{ __('Composable Dried Fruits') }}
            </x-nav-link>
            <livewire:lang-switcher />
            {{-- @if (auth()->user->type == 'admin') --}}
            {{-- <button @click="$wire.togglePanel()" x-bind:class="getTheme('card').button" class="px-4 py-2 rounded">
                <span class="material-icons">settings</span>
            </button> --}}
            {{-- @endif --}}
        </div>

    </header>

</div>
