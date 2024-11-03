<?php

use function Livewire\Volt\{state};

state(['locale' => app()->getLocale()]);

$switch = function () {
    $locale = $this->locale;

    if (in_array($locale, ['ar', 'en', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
};

?>

<div>
    <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open" class="flex items-center bg-transparent border-none text-white hover:text-orange-200 transition text-md font-bold leading-5 ">
            {{ strtoupper(app()->getLocale()) }} <i class="fas fa-chevron-down mr-1 text-xs"></i>
        </button>
        <div x-show="open" class="absolute mt-2 w-40 bg-white rounded-md shadow-lg py-1 left-0">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">العربية</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
        </div>
    </div>
</div>