<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\KitchenOrderStatus;
use App\Events\KitchenOrderStatusChanged;
use App\Events\NewKitchenOrder;
use App\Models\KitchenOrder;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class TestKitchenEvents extends Command
{
    protected $signature = 'kitchen:test-events';
    protected $description = 'Test kitchen events and notifications';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Testing kitchen events...');

        // Create a test user if none exists
        $user = User::first() ?? User::factory()->create();

        // Create a test kitchen order
        $order = KitchenOrder::factory()->create([
            'status' => KitchenOrderStatus::Pending,
            'assigned_to' => $user->id,
        ]);

        // Simulate new order event
        $this->info('Simulating new order...');
        NewKitchenOrder::dispatch($order);

        // Simulate status change
        $this->info('Simulating status change...');
        KitchenOrderStatusChanged::dispatch(
            $order,
            KitchenOrderStatus::Pending,
            KitchenOrderStatus::InProgress
        );

        // Create a notification
        $this->info('Creating test notification...');
        $notificationService->createNotification(
            $user,
            'test_notification',
            [
                'title' => 'Test Kitchen Event',
                'message' => 'Test notification from kitchen events',
                'order_id' => $order->id,
            ]
        );

        $this->info('Test completed successfully!');
        return Command::SUCCESS;
    }
}
