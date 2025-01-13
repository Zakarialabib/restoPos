<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Expense;

use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Livewire\Admin\CashRegister\Create as CashRegisterCreate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class Create extends Component
{
    public $createModal = false;

    public Expense $expense;

    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required|integer|exists:expense_categories,id')]
    public $category_id;

    #[Validate('required|date')]
    public $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('nullable|min:3')]
    public $description;

    public $user_id;

    public $cash_register_id;

    public function render()
    {
        // abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.admin.expense.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->date = date('Y-m-d');

        $this->user_id = Auth::user()->id;

        if ($this->user_id) {
            $cashRegister = CashRegister::where('user_id', $this->user_id)
                ->where('status', true)
                ->first();

            if ($cashRegister) {
                $this->cash_register_id = $cashRegister->id;
            } else {
                $this->dispatch('createModal')->to(CashRegisterCreate::class);

                return;
            }
        }

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->expense = Expense::create($this->all());

        $this->expense->user()->associate(Auth::user());

        session()->flash('success', __('Expense created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;

        $this->reset(['reference', 'category_id', 'date', 'amount', 'description', 'user_id','cash_register_id']);
    }

    #[Computed]
    public function expenseCategories()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }
}
