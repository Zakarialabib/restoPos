<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Ingredient;
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

    public bool $showOrderForm = false;
    public bool $showOrderDetails = false;
    public ?Order $selectedOrder = null;
    public string $customerName = '';
    public string $customerPhone = '';
    public array $orderItems = [];
    public $products;
    public string $search = '';
    public string $status = '';
    public string $dateRange = '';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public bool $showAnalytics = false;
    public string $selectedStatus = '';

    public array $selectAll = [];
    public array $selectedOrders = [];
    public string $bulkAction = '';

    protected array $rules = [
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

    #[Computed]
    public function getOrdersProperty()
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

    public function calculateCustomItemPrice($item): float
    {
        $basePrice = $item['product']->base_price;
        $addonsPrice = collect($item['customizations'])->sum(function ($customization) {
            return $customization['quantity'] * $customization['ingredient']->price;
        });
        
        return $basePrice + $addonsPrice;
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
                $product = Product::findOrFail($item['product_id']);
                $price = $product->is_customizable ? 
                    $this->calculateCustomItemPrice($item) : 
                    $product->price;

                $orderItem = $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'customizations' => $item['customizations'] ?? null,
                ]);

                // Handle customizations inventory
                if (!empty($item['customizations'])) {
                    foreach ($item['customizations'] as $customization) {
                        $ingredient = Ingredient::find($customization['ingredient_id']);
                        if (!$ingredient->hasStock($customization['quantity'])) {
                            throw new Exception("Insufficient stock for {$ingredient->name}");
                        }
                    }
                }
            }

            $order->calculateTotal();
            DB::commit();
            
            session()->flash('success', __('Order saved successfully.'));
            $this->reset();
            
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error saving order: ') . $e->getMessage());
        }
    }

    #[Computed]
    public function getOrderAnalyticsProperty()
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

    public function processBatchOrders(array $orders): void
    {
        foreach ($orders as $order) {
            $this->validateStock($order);
        }
    }

    public function validateStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = Product::findOrFail($item->product_id);
            if ( ! $product->isProductAvailable($item->quantity)) {
                session()->flash('error', "{$product->name} is out of stock.");
            }
        }
    }
}
