<?php

declare(strict_types=1);

if ( ! function_exists('isRtl')) {
    function isRtl()
    {
        return in_array(app()->getLocale(), ['ar']);
    }
}

if ( ! function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        if ($key === null) {
            return \App\Models\Settings::all()->pluck('value', 'key');
        }
        
        $setting = \App\Models\Settings::where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }
}


