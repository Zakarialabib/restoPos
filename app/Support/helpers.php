<?php

declare(strict_types=1);

if ( ! function_exists('isRtl')) {
    function isRtl()
    {
        return in_array(app()->getLocale(), ['ar']);
    }
}
