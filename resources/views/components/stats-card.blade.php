@props(['title', 'value', 'icon' => null, 'change' => null, 'footer' => null, 'color' => 'indigo'])

<div
    {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition']) }}>
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $value }}</p>

            @if (!is_null($change))
                <p class="mt-1 text-sm {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                </p>
            @endif
        </div>

        @if ($icon)
            <div class="text-4xl text-{{ $color }}-500">
                <span class="material-icons text-2xl {{ isset($iconColor) ? 'text-' . $iconColor . '-400' : 'text-yellow-400' }}">
                    {{ $icon }}
                </span>
            </div>
        @endif
    </div>

    @if ($footer)
        <div class="mt-4 text-sm text-gray-400">
            {{ $footer }}
        </div>
    @endif
</div>
