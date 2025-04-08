<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Kitchen;

use App\Enums\KitchenOrderStatus;
use App\Events\KitchenOrderStatusChanged;
use App\Models\KitchenOrder;
use App\Models\User;
use App\Services\KitchenService;
use App\Services\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kitchen Management')]
class Index extends Component
{
    public Collection $allOrders;
    public Collection $kitchenStaff;
    public Collection $performanceMetrics;
    public int $unreadNotifications = 0;
    public ?string $selectedTimeframe = 'today';
    public ?string $selectedStaff = null;

    public function mount(KitchenService $kitchenService, NotificationService $notificationService): void
    {
        $this->loadData($kitchenService);
        $this->unreadNotifications = $notificationService->getUnreadCount(Auth::user());
        $this->kitchenStaff = User::role('kitchen')->get();
    }

    #[On('kitchen.order.status.changed')]
    public function handleOrderStatusChange(array $data): void
    {
        $this->loadData(app(KitchenService::class));
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Order #{$data['order_id']} status changed to {$data['new_status']}"
        ]);
    }

    public function assignOrder(int $orderId, int $staffId): void
    {
        $order = KitchenOrder::findOrFail($orderId);
        app(KitchenService::class)->assignKitchenOrder($order, $staffId);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Order #{$orderId} assigned to staff member"
        ]);
    }

    public function updateOrderStatus(int $orderId, KitchenOrderStatus $newStatus): void
    {
        $order = KitchenOrder::findOrFail($orderId);
        $oldStatus = $order->status;

        $order->update(['status' => $newStatus]);
        KitchenOrderStatusChanged::dispatch($order, $oldStatus, $newStatus);
    }

    public function loadData(KitchenService $kitchenService): void
    {
        $query = KitchenOrder::with(['order', 'items', 'assignedTo'])
            ->when($this->selectedStaff, function ($query): void {
                $query->where('assigned_to', $this->selectedStaff);
            })
            ->when($this->selectedTimeframe, function ($query): void {
                match ($this->selectedTimeframe) {
                    'today' => $query->whereDate('created_at', today()),
                    'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereMonth('created_at', now()->month),
                    default => null,
                };
            });

        $this->allOrders = $query->latest()->get();
        $this->calculatePerformanceMetrics();
    }

    public function updatedSelectedTimeframe(): void
    {
        $this->loadData(app(KitchenService::class));
    }

    public function updatedSelectedStaff(): void
    {
        $this->loadData(app(KitchenService::class));
    }

    public function render()
    {
        return view('livewire.admin.kitchen.index');
    }

    private function calculatePerformanceMetrics(): void
    {
        $metrics = [
            'total_orders' => $this->allOrders->count(),
            'completed_orders' => $this->allOrders->where('status', KitchenOrderStatus::Completed)->count(),
            'delayed_orders' => $this->allOrders->where('status', KitchenOrderStatus::Delayed)->count(),
            'average_preparation_time' => $this->allOrders
                ->where('status', KitchenOrderStatus::Completed)
                ->avg(fn ($order) => $order->started_at ? $order->started_at->diffInMinutes($order->completed_at) : 0),
        ];

        $this->performanceMetrics = collect($metrics);
    }
}
