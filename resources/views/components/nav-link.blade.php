@props(['active' => false])

<a {{ $attributes->merge([
    'class' => ($active 
        ? 'border-blue-500 text-gray-900' 
        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700') . 
        ' inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out'
]) }}>
    {{ $slot }}
</a>
