<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Session;

class ThemeService
{
    protected $defaultTheme = [
        'appearance' => 'light',
        'accentColor' => 'orange',
        'grayColor' => 'slate',
        'radius' => 'medium',
        'scaling' => '100%',
        'tvMode' => false,
    ];

    public function __construct()
    {
        if ( ! Session::has('theme')) {
            Session::put('theme', $this->defaultTheme);
        }
    }

    public function getTheme()
    {
        return Session::get('theme', $this->defaultTheme);
    }

    public function updateTheme(array $updates)
    {
        $currentTheme = $this->getTheme();
        $newTheme = array_merge($currentTheme, $updates);
        Session::put('theme', $newTheme);
        return $newTheme;
    }

    public function resetTheme()
    {
        Session::put('theme', $this->defaultTheme);
        return $this->defaultTheme;
    }

    public function isDarkMode()
    {
        return 'dark' === $this->getTheme()['appearance'];
    }

    public function isTVMode()
    {
        return $this->getTheme()['tvMode'];
    }

    public function getAccentColor()
    {
        return $this->getTheme()['accentColor'];
    }

    public function getGrayColor()
    {
        return $this->getTheme()['grayColor'];
    }

    public function getRadius()
    {
        return $this->getTheme()['radius'];
    }

    public function getScaling()
    {
        return $this->getTheme()['scaling'];
    }

    public function getThemeClasses()
    {
        $theme = $this->getTheme();
        $classes = [];

        // Add appearance class
        $classes[] = 'dark' === $theme['appearance'] ? 'dark' : 'light';

        // Add TV mode classes
        if ($theme['tvMode']) {
            $classes[] = 'tv-mode';
            $classes[] = 'scale-120';
        }

        // Add accent color class
        $classes[] = "accent-{$theme['accentColor']}";

        // Add gray color class
        $classes[] = "gray-{$theme['grayColor']}";

        // Add radius class
        $classes[] = "radius-{$theme['radius']}";

        // Add scaling class
        $classes[] = "scale-{$theme['scaling']}";

        return implode(' ', $classes);
    }

    public function getThemeStyles()
    {
        $theme = $this->getTheme();
        $styles = [];

        // Add scaling style
        $styles[] = "--theme-scale: {$theme['scaling']};";

        // Add accent color variables
        $styles[] = "--accent-50: var(--{$theme['accentColor']}-50);";
        $styles[] = "--accent-100: var(--{$theme['accentColor']}-100);";
        $styles[] = "--accent-200: var(--{$theme['accentColor']}-200);";
        $styles[] = "--accent-300: var(--{$theme['accentColor']}-300);";
        $styles[] = "--accent-400: var(--{$theme['accentColor']}-400);";
        $styles[] = "--accent-500: var(--{$theme['accentColor']}-500);";
        $styles[] = "--accent-600: var(--{$theme['accentColor']}-600);";
        $styles[] = "--accent-700: var(--{$theme['accentColor']}-700);";
        $styles[] = "--accent-800: var(--{$theme['accentColor']}-800);";
        $styles[] = "--accent-900: var(--{$theme['accentColor']}-900);";

        // Add gray color variables
        $styles[] = "--gray-50: var(--{$theme['grayColor']}-50);";
        $styles[] = "--gray-100: var(--{$theme['grayColor']}-100);";
        $styles[] = "--gray-200: var(--{$theme['grayColor']}-200);";
        $styles[] = "--gray-300: var(--{$theme['grayColor']}-300);";
        $styles[] = "--gray-400: var(--{$theme['grayColor']}-400);";
        $styles[] = "--gray-500: var(--{$theme['grayColor']}-500);";
        $styles[] = "--gray-600: var(--{$theme['grayColor']}-600);";
        $styles[] = "--gray-700: var(--{$theme['grayColor']}-700);";
        $styles[] = "--gray-800: var(--{$theme['grayColor']}-800);";
        $styles[] = "--gray-900: var(--{$theme['grayColor']}-900);";

        return implode(' ', $styles);
    }
}
