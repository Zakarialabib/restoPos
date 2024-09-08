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
    <select name="locale" wire:model.live="locale">
        <option value="ar"{{ app()->getLocale() == 'ar' ? ' selected' : '' }}>العربية</option>
        <option value="en"{{ app()->getLocale() == 'en' ? ' selected' : '' }}>English</option>
        <option value="fr"{{ app()->getLocale() == 'fr' ? ' selected' : '' }}>Français</option>
    </select>
</div>
