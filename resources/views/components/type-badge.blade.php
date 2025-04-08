<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1 font-medium rounded-full {$colorClasses} {$bgColor} {$textColor}"
]) }}>
    <span>{{ $icon }}</span>
    <span>{{ $label }}</span>
</span> 