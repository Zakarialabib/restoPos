@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-md font-bold leading-5 text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out no-underline hover:underline'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-md font-bold leading-5 text-white hover:text-gray-100 hover:border-gray-300 focus:outline-none focus:text-gray-100 focus:border-gray-300 transition duration-150 ease-in-out no-underline hover:underline';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
