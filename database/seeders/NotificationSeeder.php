<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Notification::create([
            'type' => Notification::TYPE_ORDER,
            'title' => 'New Order Received',
            'message' => 'You have a new order.',
            'user_id' => 1,
        ]);

        Notification::create([
            'type' => Notification::TYPE_SYSTEM,
            'title' => 'System Update',
            'message' => 'The system has been updated.',
            'user_id' => 1,
        ]);
    }
}
