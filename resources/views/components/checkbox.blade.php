@props([
    'name' => 'checkbox',
    'label' => '',
    'value' => '',
    'color' => 'blue',
    'checked' => false,
    'required' => false,
    'disabled' => false,
])

<div class="flex items-center">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" @if($required) required @endif
        {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-checkbox appearance-none h-5 w-5 border-2 border-' . $color . '-500 rounded-md checked:bg-' . $color . '-500 checked:border-transparent focus:outline-none transition duration-200 ease-in-out hover:bg-' . $color . '-300 hover:border-' . $color . '-600']) }}>
    <label for="{{ $name }}" class="ml-2 text-gray-700">{{ $label }}</label>

    {{ $slot }}
</div>
