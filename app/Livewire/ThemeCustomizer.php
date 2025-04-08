<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class ThemeCustomizer extends Component
{
    public $isOpen = false;
    public $theme = [
        'appearance' => 'light',
        'accentColor' => 'orange',
        'grayColor' => 'slate',
        'radius' => 'medium',
        'scaling' => '100%',
        'tvMode' => false,
    ];

    public $sections = [
        'appearance' => true,
        'colors' => false,
        'typography' => false,
        'layout' => false,
        'presets' => false
    ];

    protected $listeners = ['themeUpdated' => 'handleThemeUpdate'];

    public function mount(): void
    {
        $this->theme = session('theme', $this->theme);
    }

    public function toggleSection($section): void
    {
        $this->sections[$section] = ! $this->sections[$section];
    }

    public function setAppearance($appearance): void
    {
        $this->theme['appearance'] = $appearance;
        $this->updateTheme();
    }

    public function setAccentColor($color): void
    {
        $this->theme['accentColor'] = $color;
        $this->updateTheme();
    }

    public function setGrayColor($color): void
    {
        $this->theme['grayColor'] = $color;
        $this->updateTheme();
    }

    public function setRadius($radius): void
    {
        $this->theme['radius'] = $radius;
        $this->updateTheme();
    }

    public function setScaling($scaling): void
    {
        $this->theme['scaling'] = $scaling;
        $this->updateTheme();
    }

    public function setTVMode($enabled): void
    {
        $this->theme['tvMode'] = $enabled;
        if ($enabled) {
            $this->theme['scaling'] = '120%';
            $this->theme['radius'] = 'large';
        }
        $this->updateTheme();
    }

    public function handleThemeUpdate($theme): void
    {
        $this->theme = $theme;
    }

    public function render()
    {
        return view('livewire.theme-customizer');
    }

    protected function updateTheme(): void
    {
        session(['theme' => $this->theme]);
        $this->dispatch('theme-updated', theme: $this->theme);
    }
}
