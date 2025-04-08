<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Livewire\Utils\Datatable;
use App\Models\Customer;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class Details extends Component
{
    use Datatable;

    public $model = Customer::class;

    #[Locked]
    public $customer_id;

    public $customer;

    public $orders;

    public function mount($id): void
    {
        $this->customer = Customer::where('id', $id)->firstOrFail();
        $this->customer_id = $this->customer->id;
    }

    #[Computed]
    public function orders()
    {
        $query = Order::where('customer_id', $this->customer_id)
            ->with('customer')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function customerPayments()
    {
        $query = Order::where('customer_id', $this->customer_id)
            ->with('orderpayments.order')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function totalOrders(): int|float
    {
        return $this->customerSum('total_amount');
    }

    #[Computed]
    public function totalOrderReturns(): int|float
    {
        return Order::where('refunded_amount', '>', 0)->sum('refunded_amount') / 100;
    }

    #[Computed]
    public function totalPayments(): int|float
    {
        return $this->customerSum('paid_amount') / 100;
    }

    // total due amount
    #[Computed]
    public function totalDue(): int|float
    {
        return $this->customerSum('due_amount') / 100;
    }

    #[Computed]
    public function profit(): int|float
    {
        // Step 1: Calculate total orders revenue for completed orders
        $ordersTotal = Order::where('payment_status', 'paid')
            ->sum('total_amount') / 100;

        // Step 2: Calculate total orders returns
        $orderReturnsTotal = Order::where('refunded_amount', '>', 0)
            ->sum('total_amount') / 100;

        // Step 3: Calculate the total product cost from the pivot table
        $productCosts = 0;

        // Step 4: Calculate profit
        $profit = ($ordersTotal - $orderReturnsTotal) - $productCosts;

        return $profit;
    }

    public function render()
    {
        return view('livewire.admin.customers.details');
    }

    private function customerSum(string $field): int|float
    {
        return Order::whereBelongsTo($this->customer)->sum($field) / 100;
    }
}
