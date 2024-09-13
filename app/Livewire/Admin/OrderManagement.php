<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $showOrderForm = false;
    public $showOrderDetails = false;
    public $selectedOrder;
    public $orderStatuses = ['pending', 'processing', 'completed', 'cancelled'];

    // New order form fields
    public $customerName;
    public $customerPhone;
    public $orderItems = [];
    public $products;

    protected $rules = [
        'customerName' => 'required|string|max:255',
        'customerPhone' => 'required|string|max:20',
        'orderItems' => 'required|array|min:1',
        'orderItems.*.product_id' => 'required|exists:products,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
    ];

    public function mount(): void
    {
        $this->products = Product::where('is_available', true)->get();
    }

    public function render()
    {
        $orders = Order::latest()->paginate(10);
        return view('livewire.admin.order-management', compact('orders'));
    }

    public function viewOrderDetails(Order $order): void
    {
        $this->selectedOrder = $order;
        $this->showOrderDetails = true;
    }

    public function updateOrderStatus(Order $order, $status): void
    {
        $order->update(['status' => $status]);
        $this->selectedOrder = $order->fresh();
    }

    public function toggleOrderForm(): void
    {
        $this->showOrderForm = ! $this->showOrderForm;
        $this->reset(['customerName', 'customerPhone', 'orderItems']);
    }

    public function addOrderItem(): void
    {
        $this->orderItems[] = ['product_id' => '', 'quantity' => 1];
    }

    public function removeOrderItem($index): void
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
    }

    public function createOrder(): void
    {
        $this->validate();

        $order = Order::create([
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'status' => 'pending',
            'total_amount' => $this->calculateTotal(),
        ]);

        foreach ($this->orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => Product::find($item['product_id'])->price,
            ]);
        }

        $this->toggleOrderForm();
        session()->flash('message', 'Order created successfully.');
    }

    private function calculateTotal()
    {
        $total = 0;
        foreach ($this->orderItems as $item) {
            $product = Product::find($item['product_id']);
            $total += $product->price * $item['quantity'];
        }
        return $total;
    }
}
