<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Kitchen;

use App\Enums\KitchenOrderStatus;
use App\Events\KitchenOrderStatusChanged;
use App\Models\KitchenOrder;
use App\Services\KitchenService;
use App\Services\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    public Collection $pendingOrders;
    public Collection $inProgressOrders;
    public Collection $completedOrders;
    public Collection $delayedOrders;
    public int $unreadNotifications = 0;

    public function mount(KitchenService $kitchenService, NotificationService $notificationService): void
    {
        $this->loadOrders($kitchenService);
        $this->unreadNotifications = $notificationService->getUnreadCount(Auth::user());
    }

    #[On('kitchen.order.status.changed')]
    public function handleOrderStatusChange(array $data): void
    {
        $this->loadOrders(app(KitchenService::class));
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Order #{$data['order_id']} status changed to {$data['new_status']}"
        ]);
    }

    #[On('notification.received')]
    public function handleNewNotification(): void
    {
        $this->unreadNotifications++;
    }

    public function updateOrderStatus(int $orderId, KitchenOrderStatus $newStatus): void
    {
        $order = KitchenOrder::findOrFail($orderId);
        $oldStatus = $order->status;

        $order->update(['status' => $newStatus]);

        KitchenOrderStatusChanged::dispatch($order, $oldStatus, $newStatus);
    }

    public function render()
    {
        return view('livewire.kitchen.dashboard');
    }

    private function loadOrders(KitchenService $kitchenService): void
    {
        $this->pendingOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::Pending);
        $this->inProgressOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::InProgress);
        $this->completedOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::Completed);
        $this->delayedOrders = $kitchenService->getDelayedOrders();
    }
}
