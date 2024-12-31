@props(['title' => null, 'value' => null, 'comparison' => null])
<div class="bg-white rounded-lg shadow p-4">
    <h4 class="text-sm font-medium text-gray-500">{{ $title }}</h4>
    <div class="mt-2 flex items-baseline">
        <p class="text-2xl font-semibold text-gray-900">
            {{ $value }}
        </p>
        @if($comparison)
            <p class="ml-2 flex items-baseline text-sm font-semibold">
                {{ $comparison }}
            </p>
        @endif
    </div>
</div> 