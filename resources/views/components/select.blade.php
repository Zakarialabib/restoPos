@props(['options', 'name' => null, 'label' => null, 'placeholder' => 'Select an option', 'required' => false, 'disabled' => false, 'multiple' => false, 'error' => null, 'value' => null])

@php
    $id = Str::random(10);
@endphp

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
        {{ $label ?? '' }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
    >
        @if(!$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $option)
            @php
                $optionValue = $option['value'] ?? $option;
                $optionLabel = $option['label'] ?? (is_object($option) && method_exists($option, 'label') ? $option->label() : $option);
                $isSelected = $value == $optionValue || 
                    (is_object($value) && method_exists($value, 'value') && $value->value == $optionValue);
            @endphp
            <option value="{{ $optionValue }}" @if($isSelected) selected @endif>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @if($error)
        <p class="mt-2 text-sm text-red-600" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>