@props([
    'id' => null,
    'type' => 'text',
    'name' => null,
    'value' => null,
    'disabled' => false,
    'required' => false,
    'placeholder' => null,
    'autofocus' => false,
    'autocomplete' => null,
    'readonly' => false,
])

@php
    $attributes = $attributes->class([
        'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2',
        'disabled:opacity-50' => $disabled,
        'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red-500' => $errors->has($name),
    ])->merge([
        'id' => $id,
        'type' => $type,
        'name' => $name,
        'value' => $value,
        'disabled' => $disabled,
        'required' => $required,
        'placeholder' => $placeholder,
        'autofocus' => $autofocus,
        'autocomplete' => $autocomplete,
        'readonly' => $readonly,
    ]);
@endphp

<input {{ $attributes }} dir="ltr" wire:dirty.class="border-red-500" />
