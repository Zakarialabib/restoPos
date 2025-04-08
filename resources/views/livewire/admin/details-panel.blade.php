<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $title ?? 'Details' }}
        </h3>
        @if(isset($actions))
            <div class="flex space-x-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    <div class="space-y-4">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="mt-6 border-t pt-4">
            {{ $footer }}
        </div>
    @endif
</div> 