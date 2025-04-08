<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\On;
use Livewire\Component;

class Cache extends Component
{

    public function render()
    {
        return view('livewire.utils.cache');
    }

    #[On('onClearCache')]
    public function onClearCache(): void
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');

        $this->alert('success', __('All caches have been cleared!'));
    }
}
