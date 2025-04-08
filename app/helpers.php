<?php

declare(strict_types=1);

if ( ! function_exists('isRtl')) {
    function isRtl()
    {
        return in_array(app()->getLocale(), ['ar']);
    }
}

if (! function_exists('settings')) {
    function settings($key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('settings', function () {
            return \App\Models\Settings::pluck('value', 'key');
        })->get($key, $default);
    }
}