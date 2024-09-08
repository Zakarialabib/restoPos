<?php

if (!function_exists('isRtl')) {
    function isRtl()
    {
        return in_array(app()->getLocale(), ['ar']);
    }
}