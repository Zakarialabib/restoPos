<div {{ $attributes->merge(['class' => 'bg-gray-800 rounded-lg p-6 transition-all duration-300 hover:bg-gray-700']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $value }}</p>
            @if(isset($change))
                <p class="mt-1 text-sm {{ $change >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                </p>
            @endif
        </div>
        @if(isset($icon))
            <div class="p-3 bg-gray-700 rounded-full">
                <span class="material-icons text-2xl {{ isset($iconColor) ? $iconColor : 'text-blue-400' }}">
                    {{ $icon }}
                </span>
            </div>
        @endif
    </div>
    @if(isset($footer))
        <div class="mt-4 text-sm text-gray-400">
            {{ $footer }}
        </div>
    @endif
</div>