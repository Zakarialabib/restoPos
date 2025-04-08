<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\PurchaseOrderService;
use Illuminate\Console\Command;

class GeneratePurchaseOrders extends Command
{
    protected $signature = 'inventory:generate-purchase-orders';
    protected $description = 'Generate purchase orders for low stock items';

    public function __construct(
        private readonly PurchaseOrderService $purchaseOrderService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Generating purchase orders for low stock items...');

        $purchaseOrders = $this->purchaseOrderService->generatePurchaseOrders();

        if ($purchaseOrders->isEmpty()) {
            $this->info('No purchase orders were generated.');
            return Command::SUCCESS;
        }

        $this->info("Generated {$purchaseOrders->count()} purchase orders:");
        foreach ($purchaseOrders as $order) {
            $this->line("- Purchase Order #{$order->id} for {$order->items->count()} items");
        }

        return Command::SUCCESS;
    }
}
