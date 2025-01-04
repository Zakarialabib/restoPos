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
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $showBulkActions = false;
    public string $timeFilter = 'all';
    public bool $onlyUnpaid = false;

    public array $selectAll = [];
    public array $selectedOrders = [];
    public string $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateRange' => ['except' => ''],
        'timeFilter' => ['except' => 'all'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'onlyUnpaid' => ['except' => false],
    ];

    public function mount(): void
    {
        $this->products = Product::available()->get();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedOrders = $this->orders->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedOrders = [];
        }
    }

    public function bulkUpdateStatus(string $status): void
    {
        if (empty($this->selectedOrders)) {
            $this->addError('bulk', 'Please select orders to update');
            return;
        }

        Order::whereIn('id', $this->selectedOrders)->update(['status' => $status]);
        $this->selectedOrders = [];
        $this->selectAll = [];
        session()->flash('success', 'Orders updated successfully');
    }

    public function bulkMarkAsPaid(): void
    {
        if (empty($this->selectedOrders)) {
            $this->addError('bulk', 'Please select orders to mark as paid');
            return;
        }

        Order::whereIn('id', $this->selectedOrders)->update(['is_paid' => true]);
        $this->selectedOrders = [];
        $this->selectAll = [];
        session()->flash('success', 'Orders marked as paid successfully');
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();
        session()->flash('success', __('Order deleted successfully.'));
    }

    public function markAsPaid(Order $order): void
    {
        $order->update(['is_paid' => true]);
        session()->flash('success', __('Order marked as paid.'));
    }

    #[Computed]
    public function orders()
    {
        return Order::query()
            ->with(['items.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                        ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->when($this->onlyUnpaid, fn($query) => $query->where('is_paid', false))
            ->when($this->timeFilter, function ($query) {
                return match ($this->timeFilter) {
                    'today' => $query->whereDate('created_at', today()),
                    'yesterday' => $query->whereDate('created_at', today()->subDay()),
                    'this_week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'this_month' => $query->whereMonth('created_at', now()->month),
                    default => $query
                };
            })
            ->when($this->dateRange, function ($query) {
                [$start, $end] = explode(' - ', $this->dateRange);
                return $query->whereBetween('created_at', [$start, $end . ' 23:59:59']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function viewOrderDetails(Order $order): void
    {
        $this->selectedOrder = $order->load(['items.product']);
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
                session()->flash('success', 'Order completed successfully.');
            } catch (Exception $e) {
                Log::error('Order inventory update failed: ' . $e->getMessage());
                $this->addError('order', 'Order completed with errors: ' . $e->getMessage());
            }
        }
    }

    #[Computed]
    public function orderAnalytics()
    {
        $query = Order::query();

        if ($this->dateRange) {
            [$start, $end] = explode(' - ', $this->dateRange);
            $query->whereBetween('created_at', [$start, $end . ' 23:59:59']);
        }

        $orders = $query->get();
        $totalRevenue = $orders->sum('total_amount');
        $totalProfit = $orders->sum(function ($order) {
            return $order->getProfit();
        });

        return [
            'total_revenue' => $totalRevenue,
            'total_profit' => $totalProfit,
            'average_order_value' => $orders->count() > 0 ? $totalRevenue / $orders->count() : 0,
            'order_count' => $orders->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.order-management');
    }
}
