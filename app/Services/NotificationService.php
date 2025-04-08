<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use App\Models\User;
use App\Notifications\ExpiringIngredient;
use App\Notifications\FifoWarningNotification;
use App\Notifications\LowStockAlert;
use App\Notifications\PurchaseOrderGeneratedNotification;
use App\Notifications\StockLevel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications->count();
    }

    public function getNotifications(User $user): Collection
    {
        return $user->notifications()
            ->latest()
            ->take(50)
            ->get()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ]);
    }

    public function markAsRead(User $user, string $notificationId): bool
    {
        return $user->notifications()
            ->where('id', $notificationId)
            ->update(['read_at' => now()]) > 0;
    }

    public function markAllAsRead(User $user): bool
    {
        return $user->unreadNotifications->markAsRead() > 0;
    }

    public function createNotification(User $user, string $type, array $data): void
    {
        DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function notifyLowStock(User $user, Ingredient $ingredient): void
    {
        $user->notify(new LowStockAlert($ingredient));
    }

    public function notifyExpiringIngredients(User $user, Ingredient $ingredient): void
    {
        $user->notify(new ExpiringIngredient($ingredient));
    }

    public function notifyFifoWarning(User $user, Ingredient $ingredient): void
    {
        $user->notify(new FifoWarningNotification($ingredient));
    }

    public function notifyPurchaseOrderGenerated(User $user, array $purchaseOrders): void
    {
        $user->notify(new PurchaseOrderGeneratedNotification($purchaseOrders));
    }

    public function notifyStockLevel(User $user, Ingredient $ingredient): void
    {
        $user->notify(new StockLevel($ingredient));
    }
}
