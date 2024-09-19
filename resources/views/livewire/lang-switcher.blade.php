<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{state};
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
    <x-dropdown>
        <x-slot name="trigger">
            <p class="mr-1 text-white">{{ strtoupper(app()->getLocale()) }}</p>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link as="button" wire:click="$set('locale', 'ar')" class="{{ app()->getLocale() == 'ar' ? 'font-bold' : '' }}">
                العربية
            </x-dropdown-link>
            <x-dropdown-link as="button" wire:click="$set('locale', 'en')" class="{{ app()->getLocale() == 'en' ? 'font-bold' : '' }}">
                English
            </x-dropdown-link>
            <x-dropdown-link as="button" wire:click="$set('locale', 'fr')" class="{{ app()->getLocale() == 'fr' ? 'font-bold' : '' }}">
                Français
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div>