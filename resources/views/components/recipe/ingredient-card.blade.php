@props(['ingredient', 'selected' => false])

<div
    {{ $attributes->merge([
        'class' =>
            'p-4 rounded-lg border transition-colors cursor-pointer ' .
            ($selected ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-primary-300'),
    ]) }}>
    <div class="flex items-start justify-between">
        <div>
            <h3 class="font-medium text-gray-900">{{ $ingredient->name }}</h3>
            <p class="text-sm text-gray-500">{{ $ingredient->description }}</p>
        </div>
        @if ($selected)
            <x-icon name="check-circle" class="w-5 h-5 text-primary-500" />
        @endif
    </div>

    <div class="mt-3 space-y-2">
        <div class="flex items-center text-sm text-gray-500">
            <x-icon name="scale" class="w-4 h-4 mr-1" />
            {{ __('Stock') }}: {{ $ingredient->stock }} {{ $ingredient->unit }}
        </div>

        <div class="flex items-center text-sm text-gray-500">
            <x-icon name="currency-dollar" class="w-4 h-4 mr-1" />
            {{ __('Cost') }}: {{ number_format($ingredient->cost, 2) }}
        </div>

        @if ($selected)
            <div class="mt-3">
                <x-input-group class="mb-2">
                    <x-label for="{{ __('Quantity') }}"></x-label>
                    <x-input type="number" wire:model="selectedIngredients.{{ $ingredient->id }}.quantity"
                        step="0.01" min="0" max="{{ $ingredient->stock }}" />
                </x-input-group>

                <x-input-group>
                    <x-label>{{ __('Preparation Notes') }}</x-label>
                    <x-textarea wire:model="selectedIngredients.{{ $ingredient->id }}.preparation_notes"
                        rows="2" />
                </x-input-group>
            </div>
        @endif
    </div>
</div>
