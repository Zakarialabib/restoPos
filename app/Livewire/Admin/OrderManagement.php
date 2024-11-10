<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $showOrderForm = false;
    public $showOrderDetails = false;
    public $selectedOrder;
    public $customerName;
    public $customerPhone;
    public $orderItems = [];
    public $products;
    public $search = '';
    public $status = '';
    public $dateRange = '';
    public $startDate;
    public $endDate;
    public $showAnalytics = false;
    public $selectedStatus = '';

    public $selectAll = [];

    public $selectedOrders = [];

    public $bulkAction = '';

    protected $rules = [
        'customerName' => 'required|string|max:255',
        'customerPhone' => 'required|string|max:20',
        'orderItems' => 'required|array|min:1',
        'orderItems.*.product_id' => 'required|exists:products,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
    ];

    public function mount(): void
    {
        $this->products = Product::available()->get();
    }


    #[Computed()]
    public function orders()
    {
        return Order::query()
            ->when(
                $this->search,
                fn ($query) =>
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->status,
                fn ($query) =>
                $query->where('status', $this->status)
            )
            ->when($this->dateRange, function ($query) {
                [$start, $end] = explode(' - ', $this->dateRange);
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->latest()
            ->paginate(10);
    }

    public function viewOrderDetails(Order $order): void
    {
        $this->selectedOrder = $order->load(['items.product']);
        $this->showOrderDetails = true;
    }

    public function updateOrderStatus(Order $order, OrderStatus $status): void
    {
        if (OrderStatus::Completed === $status && ! $order->validate()) {
            $this->addError('order', 'Cannot complete order - insufficient stock');
            return;
        }

        $order->updateStatus($status);

        if (OrderStatus::Completed === $status) {
            $order->updateInventory();
        }
    }

    public function saveOrder(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'status' => OrderStatus::Pending,
            ]);

            foreach ($this->orderItems as $item) {
                $product = Product::find($item['product_id']);
                
                // Check product availability before creating order
                if (!$product->isProductAvailable($item['quantity'])) {
                    $this->addError('order', "Insufficient stock for {$product->name}");
                    DB::rollBack();
                    return;
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            $order->calculateTotal();
            
            // Process order and update ingredient stocks
            if ($order->processOrderIngredients()) {
                $order->update(['status' => OrderStatus::Completed]);
                DB::commit();
                session()->flash('success', 'Order processed successfully');
            } else {
                DB::rollBack();
                $this->addError('order', 'Unable to process order due to stock limitations');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Processing Error: ' . $e->getMessage());
            $this->addError('order', 'An error occurred while processing the order');
        }
    }

    #[Computed()]
    public function orderAnalytics()
    {
        return [
            'total_revenue' => $this->orders->sum('total_revenue'),
            'total_profit' => $this->orders->sum('profit'),
            'average_order_value' => $this->orders->avg('total_amount'),
            'order_count' => $this->orders->count(),
        ];
    }

    public function bulkUpdateStatus(OrderStatus $status): void
    {
        $selectedOrders = Order::whereIn('id', $this->selectedOrders)->get();

        DB::transaction(function () use ($selectedOrders, $status): void {
            foreach ($selectedOrders as $order) {
                $this->updateOrderStatus($order, $status);
            }
        });
    }

    public function validateOrder(Order $order): bool
    {
        return $order->validate();
    }

    public function render()
    {
        return view('livewire.admin.order-management');
    }

    public function processBatchOrders($orders)
    {
        foreach ($orders as $order) {
            $this->validateStock($order);
            // Additional processing logic...
        }
    }

    public function validateStock($order)
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product->stock < $item->quantity) {
                $this->addError('stock', "{$product->name} is out of stock.");
            }
        }
    }
}
