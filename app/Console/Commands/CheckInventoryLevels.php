<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Notifications\InventoryAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckInventoryLevels extends Command
{
    protected $signature = 'inventory:check';
    protected $description = 'Check inventory levels and generate notifications for low stock and expiring items';

    public function handle(): int
    {
        $this->checkLowStock();
        $this->checkExpiringItems();

        return Command::SUCCESS;
    }

    private function checkLowStock(): void
    {
        $lowStockItems = Ingredient::lowStock()->get();

        foreach ($lowStockItems as $ingredient) {
            Notification::route('mail', config('inventory.notification_email'))
                ->notify(new InventoryAlert(
                    ingredient: $ingredient,
                    type: 'low_stock',
                ));
        }
    }

    private function checkExpiringItems(): void
    {
        $expiringItems = Ingredient::expiringSoon()->get();

        foreach ($expiringItems as $ingredient) {
            $expiringBatches = $ingredient->getExpiringBatches();

            foreach ($expiringBatches as $batch) {
                Notification::route('mail', config('inventory.notification_email'))
                    ->notify(new InventoryAlert(
                        ingredient: $ingredient,
                        type: 'expiring',
                        details: [
                            'expiry_date' => $batch->expiry_date->format('Y-m-d'),
                            'quantity' => $batch->quantity,
                            'batch_number' => $batch->batch_number,
                        ],
                    ));
            }
        }
    }
}
