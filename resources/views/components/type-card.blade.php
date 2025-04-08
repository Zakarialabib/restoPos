<div {{ $attributes->merge(['class' => "rounded-xl shadow-sm bg-gradient-to-br {$bgGradient} p-4"]) }}>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold {{ $textColor }}">
                {{ $type->label() }}
            </h3>
            <p class="mt-2 text-3xl font-bold {{ $textColor }}">
                {{ $total }}
            </p>
            <p class="mt-1 text-sm {{ $textColor }}">
                {{ $active }} {{ __('active') }}
            </p>
            @if($subtitle)
                <p class="mt-1 text-xs {{ $textColor }} opacity-75">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        <div class="rounded-full {{ $iconBg }} p-3">
            <span class="text-2xl">{{ $type->icon() }}</span>
        </div>
    </div>
</div> 