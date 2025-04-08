<?php

declare(strict_types=1);

namespace App\Livewire\Admin\CashRegister;

use App\Enums\OrderStatus;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $showModal = false;

    public $cashRegister;

    public $total_sale_amount;

    public $total_payment;

    public $cash_payment;

    public $cheque_payment;

    public $total_sale_return;

    public $total_expense;

    public $total_cash;

    #[On('showModal')]
    public function showModal($id): void
    {
        $this->cashRegister = CashRegister::find($id);

        $this->total_sale_amount = Order::where([
            ['cash_register_id', $this->cashRegister->id],
            ['status', OrderStatus::Completed],
        ])->sum('total_amount') / 100;

        $this->total_payment = Order::where('cash_register_id', $this->cashRegister->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount') / 100;

        $this->cash_payment = Order::where([
            ['cash_register_id', $this->cashRegister->id],
            ['payment_method', 'Cash'],
        ])->sum('amount') / 100;

        $this->total_sale_return = Order::where('cash_register_id', $this->cashRegister->id)
            ->where('refunded_amount', '>', 0)
            ->sum('refunded_amount') / 100;

        $this->total_expense = Expense::where('cash_register_id', $this->cashRegister->id)
            ->sum('amount') / 100;

        $this->total_cash = ($this->cashRegister->cash_in_hand / 100) + $this->total_payment - ($this->total_sale_return + $this->total_expense);

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->cashRegister->status = false;

        $this->cashRegister->save();

        session()->flash('success', __('Cash register closed successfully'));
    }

    public function render()
    {
        // abort_if(Gate::denies('cashRegister_show'), 403);

        return view('livewire.admin.cash-register.show');
    }
}
