<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Orders;

use App\Enums\OrderStatus;
use App\Livewire\Utils\Datatable;
use App\Models\Order;
use App\Services\OrderProcessingService;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Order Management')]
class Index extends Component
{
    use Datatable;
    use WithPagination;

    #[Validate('string|nullable')]
    public $status = '';

    #[Validate('string|nullable')]
    public $paymentStatus = '';

    #[Validate('string|nullable')]
    public $timeFilter = '';

    #[Validate('boolean')]
    public $onlyUnpaid = false;

    #[Validate('array')]
    public array $selectedOrders = [];

    // UI States
    public $showAnalytics = false;
    public $showBulkActions = false;
    public $showOrderDetails = false;
    public ?Order $selectedOrder = null;

    public $model = Order::class;

    // Services
    protected OrderService $orderService;
    protected OrderProcessingService $orderProcessingService;
    protected OrderStatusService $orderStatusService;

    public function boot(
        OrderService $orderService,
        OrderProcessingService $orderProcessingService,
        OrderStatusService $orderStatusService
    ): void {
        $this->orderService = $orderService;
        $this->orderProcessingService = $orderProcessingService;
        $this->orderStatusService = $orderStatusService;
    }

    #[Computed]
    public function orders()
    {
        return Order::query()->with(['items.product'])
            // ->advancedFilter([
            //     's'               => $this->search ?: null,
            //     'order_column'    => $this->sortField,
            //     'order_direction' => $this->sortDirection,
            // ])
            ->when(
                $this->status,
                fn ($query) =>
                $query->where('status', $this->status)
            )
            ->when(
                $this->paymentStatus,
                fn ($query) =>
                $query->where('is_paid', 'paid' === $this->paymentStatus)
            )
            ->when(
                $this->onlyUnpaid,
                fn ($query) =>
                $query->where('is_paid', false)
            )
            ->when($this->timeFilter, function ($query) {
                return match ($this->timeFilter) {
                    'today' => $query->whereDate('created_at', Carbon::today()),
                    'yesterday' => $query->whereDate('created_at', Carbon::yesterday()),
                    'this_week' => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'this_month' => $query->whereMonth('created_at', Carbon::now()->month),
                    default => $query
                };
            })
            ->paginate(10);
    }

    #[Computed]
    public function orderAnalytics()
    {
        return [
            'total_revenue' => $this->orderService->calculateTotalRevenue(),
            'total_profit' => $this->orderService->calculateTotalProfit(),
            'average_order_value' => $this->orderService->calculateAverageOrderValue(),
            'order_count' => $this->orderService->getOrderCount()
        ];
    }

    #[Computed]
    public function customerInsights()
    {
        return [
            'total_customers' => $this->orderService->getTotalCustomers(),
            'repeat_customers' => $this->orderService->getRepeatCustomers(),
            'top_customers' => $this->orderService->getTopCustomers()
        ];
    }

    #[Computed]
    public function orderTrends()
    {
        return [
            'daily_orders' => $this->orderService->getDailyOrders(),
            'daily_revenue' => $this->orderService->getDailyRevenue(),
            'daily_profit' => $this->orderService->getDailyProfit()
        ];
    }

    #[Computed]
    public function paymentStatuses()
    {
        return [
            ['value' => 'paid', 'label' => __('Paid')],
            ['value' => 'unpaid', 'label' => __('Unpaid')]
        ];
    }

    public function viewOrderDetails(Order $order): void
    {
        $this->selectedOrder = $order->load(['items.product', 'customer']);
        $this->showOrderDetails = true;
    }

    public function markAsPaid(Order $order): void
    {
        try {
            $this->orderProcessingService->markAsPaid($order);
            session()->flash('success', __('Order marked as paid successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error marking order as paid: ') . $e->getMessage());
        }
    }

    public function updateOrderStatus($orderId, $status): void
    {
        try {
            $this->orderStatusService->updateStatus($orderId, $status);
            session()->flash('success', __('Order status updated successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error updating order status: ') . $e->getMessage());
        }
    }

    public function bulkUpdateStatus(string $status): void
    {
        if (empty($this->selectedOrders)) {
            session()->flash('error', __('Please select orders to update.'));
            return;
        }

        try {
            $this->orderProcessingService->bulkUpdateStatus($this->selectedOrders, OrderStatus::from($status));
            $this->selectedOrders = [];
            session()->flash('success', __('Orders updated successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error updating orders: ') . $e->getMessage());
        }
    }

    public function bulkMarkAsPaid(): void
    {
        if (empty($this->selectedOrders)) {
            session()->flash('error', __('Please select orders to mark as paid.'));
            return;
        }

        try {
            $this->orderProcessingService->bulkMarkAsPaid($this->selectedOrders);
            $this->selectedOrders = [];
            session()->flash('success', __('Orders marked as paid successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error marking orders as paid: ') . $e->getMessage());
        }
    }

    public function deleteOrder(Order $order): void
    {
        try {
            $this->orderService->deleteOrder($order);
            session()->flash('success', __('Order deleted successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error deleting order: ') . $e->getMessage());
        }
    }

    public function toggleAnalytics(): void
    {
        $this->showAnalytics = ! $this->showAnalytics;
    }

    public function render()
    {
        return view('livewire.admin.orders.index');
    }
}
