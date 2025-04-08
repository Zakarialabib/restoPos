<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $showModal = false;

    public $customer;

    #[On('showModal')]
    public function openModal($id): void
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->customer = Customer::find($id);

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.customers.show');
    }
}
