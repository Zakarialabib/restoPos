<?php

namespace App\Livewire;

use Livewire\Component;

class Index extends Component
{

    public function render()
    {
        // Check if application is installed
        if (!settings('is_installed', false)) {
            return view('installation.index');
        }
        
        // Determine which homepage to show based on settings
        $homepageType = settings('homepage_type', 'welcome');
        
        if ($homepageType === 'shop') {
            return view('livewire.shop.menu-index');
        }
        
        return view('livewire.welcome');
    }
}
