{{-- @if (auth()->user()->name == 'admin') --}}
<!-- Theme Panel -->
<div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full" x-bind:class="getTheme('card').bg"
    class="fixed inset-y-0 right-0 w-72 shadow-lg p-6 overflow-y-auto" x-on:click.away="$wire.isOpen = false"
    x-on:keydown.escape="$wire.isOpen = false" x-cloak>
    <h2 x-bind:class="getTheme('text')" class="text-2xl font-bold mb-4">{{ __('Theme Options') }}</h2>
    <div class="mb-8 grid grid-cols-2 gap-4">
        @foreach (['earth', 'pastel', 'vibrant', 'monochrome', 'dark', 'ocean', 'forest', 'sunset', 'neon', 'elegant', 'rustic', 'fresh'] as $theme)
            <button wire:click="$set('currentTheme', '{{ $theme }}')"
                x-bind:class="[getTheme('card').button, currentTheme === '{{ $theme }}' ?
                    'ring-2 ring-offset-2 ring-opacity-60' : ''
                ]"
                class="px-4 py-2 rounded-full text-sm">
                {{ ucfirst($theme) }}
            </button>
        @endforeach
    </div>
    <button @click="$wire.togglePanel()" x-bind:class="getTheme('card').button"
        class="w-full mt-4 px-4 py-2 rounded-full">
        x
    </button>
</div>
{{-- @endif --}}
