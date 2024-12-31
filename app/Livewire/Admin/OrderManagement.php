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
    public function orders()
    {
        return Order::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->status,
                fn($query) =>
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
        $this->selectedOrder = Order::where('id', $order->id)->first();
        $this->showOrderDetails = true;
    }

    public function updateOrderStatus(Order $order, OrderStatus $status): void
    {
        if (OrderStatus::Completed === $status) {
            $this->addError('order', 'Cannot complete order - insufficient stock');
            return;
        }

        $order->updateStatus($status);

        if (OrderStatus::Completed === $status) {
            try {
                DB::transaction(function () use ($order): void {
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        if (! $product) {
                            continue;
                        }

                        foreach ($product->ingredients as $ingredient) {
                            $requiredQuantity = $ingredient->pivot->stock * $item->quantity;

                            if (! $ingredient->hasEnoughStock($requiredQuantity)) {
                                throw new Exception("Insufficient stock for ingredient: {$ingredient->name}");
                            }

                            $ingredient->updateStock(-$requiredQuantity);
                        }
                    }
                });
                $this->addError('order', 'Order completed successfully.');
            } catch (Exception $e) {
                Log::error('Order inventory update failed: ' . $e->getMessage());
                $this->addError('order', 'Order completed with errors: ' . $e->getMessage());
            }
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
                $product = Product::findOrFail($item['product_id']);
                $price = $product->is_customizable ?
                    $this->calculateCustomItemPrice($item) :
                    $product->price;

                $orderItem = $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);
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
            if (! $product->isProductAvailable($item->quantity)) {
                session()->flash('error', "{$product->name} is out of stock.");
            }
        }
    }
}
