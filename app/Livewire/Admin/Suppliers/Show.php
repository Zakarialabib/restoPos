<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $showModal = false;

    public $supplier;

    #[On('showModal')]
    public function openModal($id): void
    {
        abort_if(Gate::denies('supplier_show'), 403);

        $this->supplier = Supplier::find($id);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.suppliers.show');
    }
}
