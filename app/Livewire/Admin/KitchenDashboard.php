<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\KitchenOrderStatus;
use App\Models\KitchenOrder;
use App\Models\KitchenOrderItem;
use App\Services\KitchenService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kitchen Dashboard')]
class KitchenDashboard extends Component
{
    use WithPagination;

    public array $activeOrders = [];
    public ?string $selectedOrder = null;
    public ?string $selectedItem = null;
    public ?string $status = null;
    public array $stats = [];
    public bool $showFilters = false;
    public ?string $priorityFilter = null;
    public ?string $statusFilter = null;
    public ?string $assignedToFilter = null;

    #[Computed]
    public function orders()
    {
        return KitchenOrder::with(['items.orderItem.product', 'assignedTo'])
            ->when($this->statusFilter, function ($query): void {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query): void {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->assignedToFilter, function ($query): void {
                $query->where('assigned_to', $this->assignedToFilter);
            })
            ->whereIn('status', [KitchenOrderStatus::Pending, KitchenOrderStatus::InProgress])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
    }

    #[Computed]
    public function stats()
    {
        return [
            'pending_orders' => KitchenOrder::where('status', KitchenOrderStatus::Pending)->count(),
            'in_progress_orders' => KitchenOrder::where('status', KitchenOrderStatus::InProgress)->count(),
            'completed_today' => KitchenOrder::where('status', KitchenOrderStatus::Completed)
                ->whereDate('completed_at', today())
                ->count(),
            'average_preparation_time' => KitchenOrder::where('status', KitchenOrderStatus::Completed)
                ->whereDate('completed_at', today())
                ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, completed_at)')) ?? 0,
            'total_items_today' => KitchenOrderItem::whereHas('kitchenOrder', function ($query): void {
                $query->whereDate('created_at', today());
            })->count(),
            'completed_items_today' => KitchenOrderItem::whereHas('kitchenOrder', function ($query): void {
                $query->whereDate('completed_at', today());
            })->where('status', KitchenOrderStatus::Completed)->count(),
        ];
    }

    public function mount(KitchenService $kitchenService): void
    {
        $this->loadActiveOrders($kitchenService);
    }

    public function loadActiveOrders(KitchenService $kitchenService): void
    {
        $this->activeOrders = $kitchenService->getActiveOrders();
    }

    public function assignOrder(string $orderId): void
    {
        $order = KitchenOrder::findOrFail($orderId);
        $kitchenService = app(KitchenService::class);
        $kitchenService->assignKitchenOrder($order, Auth::id());
        $this->loadActiveOrders($kitchenService);
    }

    public function updateItemStatus(string $itemId, string $status): void
    {
        $item = KitchenOrderItem::findOrFail($itemId);
        $kitchenService = app(KitchenService::class);
        $kitchenService->updateItemStatus($item, $status);
        $this->loadActiveOrders($kitchenService);
    }

    public function resetFilters(): void
    {
        $this->priorityFilter = null;
        $this->statusFilter = null;
        $this->assignedToFilter = null;
        $this->resetPage();
    }

    #[On('order-created')]
    #[On('order-updated')]
    #[On('echo:orders,OrderCreated')]
    #[On('echo:orders,OrderUpdated')]
    #[On('echo:kitchen,kitchen.order.status.changed')]
    public function handleOrderUpdate(): void
    {
        $this->loadActiveOrders(app(KitchenService::class));
    }

    public function render()
    {
        return view('livewire.admin.kitchen-dashboard');
    }
}
