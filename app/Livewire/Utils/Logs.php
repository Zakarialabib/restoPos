<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Logs extends Component
{
    public function render()
    {
        $logs = File::files(storage_path('logs'));

        return view('livewire.utils.logs', ['logs' => $logs]);
    }
}
