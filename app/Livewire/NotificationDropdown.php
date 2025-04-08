<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\NotificationService;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationDropdown extends Component
{
    use WithPagination;

    public bool $showDropdown = false;
    public int $unreadCount = 0;

    protected $listeners = [
        'notificationReceived' => 'updateUnreadCount',
        'notificationRead' => 'updateUnreadCount',
    ];

    public function mount(NotificationService $notificationService): void
    {
        $user = Auth::user();
        if ($user) {
            $this->unreadCount = $notificationService->getUnreadCount($user);
        }
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = ! $this->showDropdown;
    }

    public function markAsRead(string $notificationId, NotificationService $notificationService): void
    {
        $user = Auth::user();
        if ($user) {
            /** @var DatabaseNotification $notification */
            $notification = $user->notifications()->findOrFail($notificationId);
            $notification->markAsRead();
            $this->updateUnreadCount($notificationService);
            $this->dispatch('notificationRead');
        }
    }

    public function markAllAsRead(NotificationService $notificationService): void
    {
        $user = Auth::user();
        if ($user) {
            $notificationService->markAllAsRead($user);
            $this->updateUnreadCount($notificationService);
            $this->dispatch('notificationRead');
        }
    }

    public function updateUnreadCount(NotificationService $notificationService): void
    {
        $user = Auth::user();
        if ($user) {
            $this->unreadCount = $notificationService->getUnreadCount($user);
        }
    }

    public function render(): \Illuminate\View\View
    {
        $user = Auth::user();
        $notifications = collect([]);

        if ($user) {
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.notification-dropdown', [
            'notifications' => $notifications,
        ]);
    }
}
