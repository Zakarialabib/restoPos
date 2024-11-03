@props(['title', 'value', 'icon', 'color' => 'blue'])

<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
            @if (isset($description))
                <p class="text-sm text-gray-600">{{ $description }}</p>
            @endif
        </div>
        <div>
            {{ $value }}
        </div>
    </div>
</div>
