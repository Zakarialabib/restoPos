<?php

use function Livewire\Volt\{title, layout};

title('Compose');
layout('layouts.guest');

?>

<div>
    {{-- we could compose juices/coctails or salade we will have to cards redirecting to different pages --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold">{{ __('Compose') }}</h1>
            <p class="text-sm text-gray-500">{{ __('Compose your order') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('compose.juices') }}" class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-2">{{ __('Juices') }}</h2>
        </a>
        <a href="{{ route('compose.salade') }}" class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-2">{{ __('Salade') }}</h2>
        </a>
        <a href="{{ route('compose.dried-fruits') }}" class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-2">{{ __('Dried Fruits') }}</h2>
        </a>
        <a href="#" class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-2">{{ __('Tea') }}</h2>
        </a>
    </div>

</div>
