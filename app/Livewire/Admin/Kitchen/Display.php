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

class Display extends Component
{
    public Collection $pendingOrders;
    public Collection $inProgressOrders;
    public Collection $completedOrders;
    public Collection $delayedOrders;
    public int $unreadNotifications = 0;
    public bool $showNotifications = false;
    public Collection $notifications;

    public function mount(KitchenService $kitchenService, NotificationService $notificationService): void
    {
        $this->loadOrders($kitchenService);
        $this->loadNotifications($notificationService);

        // Subscribe to kitchen channels
        $this->subscribeToChannels();
    }

    #[On('notification.received')]
    public function handleNewNotification(): void
    {
        $this->unreadNotifications++;
        $this->loadNotifications(app(NotificationService::class));
    }

    public function toggleNotifications(): void
    {
        $this->showNotifications = ! $this->showNotifications;
        if ($this->showNotifications) {
            $this->loadNotifications(app(NotificationService::class));
        }
    }

    public function markNotificationAsRead(string $notificationId): void
    {
        app(NotificationService::class)->markAsRead(Auth::user(), $notificationId);
        $this->loadNotifications(app(NotificationService::class));
    }

    public function markAllNotificationsAsRead(): void
    {
        app(NotificationService::class)->markAllAsRead(Auth::user());
        $this->loadNotifications(app(NotificationService::class));
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
        return view('livewire.kitchen.display');
    }

    private function subscribeToChannels(): void
    {
        // Listen for order status changes
        $this->dispatch('echo:kitchen', [
            'event' => 'KitchenOrderStatusChanged',
            'callback' => function ($event): void {
                $this->loadOrders(app(KitchenService::class));
                $this->dispatch('notify', [
                    'type' => 'info',
                    'message' => "Order #{$event['order_id']} status changed to {$event['new_status']}"
                ]);
            }
        ]);

        // Listen for new orders
        $this->dispatch('echo:kitchen', [
            'event' => 'NewKitchenOrder',
            'callback' => function ($event): void {
                $this->loadOrders(app(KitchenService::class));
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => "New order #{$event['order_id']} received!"
                ]);
            }
        ]);
    }

    private function loadNotifications(NotificationService $notificationService): void
    {
        $this->unreadNotifications = $notificationService->getUnreadCount(Auth::user());
        $this->notifications = $notificationService->getNotifications(Auth::user());
    }

    private function loadOrders(KitchenService $kitchenService): void
    {
        $this->pendingOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::Pending);
        $this->inProgressOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::InProgress);
        $this->completedOrders = $kitchenService->getOrdersByStatus(KitchenOrderStatus::Completed);
        $this->delayedOrders = $kitchenService->getDelayedOrders();
    }
}
