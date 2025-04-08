<?php

declare(strict_types=1);

namespace App\Livewire\Admin\CashRegister;

use App\Models\CashRegister;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    /** @var bool */
    public $createModal = false;

    public CashRegister $cashRegister;

    #[Validate('required', message: 'Please provide a cash in hand')]
    #[Validate('numeric', message: 'Cash in hand must be numeric')]
    public $cash_in_hand;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        CashRegister::create([
            'cash_in_hand' => $this->cash_in_hand,
            // 'user_id'      => auth()->user()->id,
            'status'       => true,
        ]);

        $this->dispatch('refreshIndex')->to(Index::class);

        session()->flash('success', __('CashRegister created successfully.'));

        $this->reset(['cash_in_hand']);

        $this->createModal = false;
    }

    public function render()
    {
        // abort_if(Gate::denies('cashRegister_create'), 403);

        return view('livewire.admin.cash-register.create');
    }
}
