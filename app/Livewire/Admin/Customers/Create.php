<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    public $createModal = false;

    public Customer $customer;

    #[Validate('required', message: 'The name field is required')]
    #[Validate('min:3', message: 'The name field must be more than 3 characters.')]
    #[Validate('max:255', message: 'The name field must be less 255 characters.')]
    public string $name;

    public $email;

    #[Validate('required', message: 'The phone field is required')]
    #[Validate('numeric', message: 'The phone field must be a numeric value.')]
    public $phone;

    public $city;

    public $country;

    public $address;

    public $tax_number;

    public $role;

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

        Customer::create($this->all());

        session()->flash('success', __('Customer created successfully'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }


    public function render()
    {
        return view('livewire.admin.customers.create');
    }
}
