@props(['label' => null, 'name' => null, 'type' => 'text', 'value' => '', 'error' => null, 'required' => false])

<div class="space-y-2">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
        {{ $attributes->merge([
            'class' =>
                'mt-1 block py-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500' .
                ($error ? ' border-red-500' : ''),
        ]) }}
        @if ($required) required @endif aria-describedby="{{ $name }}-error">

    @if ($error)
        <p class="mt-1 text-sm text-red-600" id="{{ $name }}-error">
            {{ $error }}
        </p>
    @endif
</div>
