<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Suppliers;

use App\Livewire\Utils\Datatable;
use App\Models\Order;
use App\Models\Supplier;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class Details extends Component
{
    use Datatable;

    #[Locked]
    public $supplier_id;

    public $model = Supplier::class;

    public $supplier;

    public $purchases;

    public function mount($id): void
    {
        $this->supplier = Supplier::findOrFail($id);
        $this->supplier_id = $this->supplier->id;
    }

    #[Computed]
    public function TotalOrders(): float
    {
        return $this->supplierSum('total_amount');
    }

    #[Computed]
    public function TotalOrderReturns(): float
    {
        return Order::where('refunded_amount', '>', 0)
            ->sum('refunded_amount') / 100;
    }

    #[Computed]
    public function TotalDue(): float
    {
        return $this->supplierSum('due_amount');
    }

    #[Computed]
    public function TotalPayments(): float
    {
        return $this->supplierSum('paid_amount');
    }

    #[Computed]
    public function Debit(): float
    {
        // Step 1: Calculate total purchases revenue for completed purchases
        $purchasesTotal = Order::where('payment_status', 'paid')
            ->sum('total_amount') / 100;

        // Step 2: Calculate total purchases returns
        $purchaseReturnsTotal = Order::where('refunded_amount', '>', 0)
            ->sum('refunded_amount') / 100;

        $debit = ($purchasesTotal - $purchaseReturnsTotal);

        return $debit;
    }


    public function render()
    {
        return view('livewire.admin.suppliers.details');
    }

    private function supplierSum(string $field): int|float
    {
        return Order::whereBelongsTo($this->supplier)->sum($field) / 100;
    }
}
