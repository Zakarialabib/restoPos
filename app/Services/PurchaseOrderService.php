<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function generatePurchaseOrders(): Collection
    {
        $lowStockItems = Ingredient::lowStock()->get();
        $purchaseOrders = collect();

        foreach ($lowStockItems as $ingredient) {
            $orderQuantity = $this->calculateOrderQuantity($ingredient);

            if ($orderQuantity <= 0) {
                continue;
            }

            $purchaseOrder = $this->createPurchaseOrder($ingredient, $orderQuantity);
            $purchaseOrders->push($purchaseOrder);
        }

        return $purchaseOrders;
    }

    public function approvePurchaseOrder(PurchaseOrder $purchaseOrder): void
    {
        DB::transaction(function () use ($purchaseOrder): void {
            $purchaseOrder->update(['status' => 'approved']);

            foreach ($purchaseOrder->items as $item) {
                $ingredient = $item->ingredient;
                $ingredient->createBatch(
                    quantity: $item->quantity,
                    batchNumber: "PO_{$purchaseOrder->id}_{$item->id}",
                    expiryDate: $purchaseOrder->expected_delivery_date->addDays($ingredient->shelf_life_days ?? 30)
                );
            }
        });
    }

    private function calculateOrderQuantity(Ingredient $ingredient): float
    {
        $currentStock = $ingredient->stock_quantity;
        $reorderPoint = $ingredient->reorder_point;
        $minimumOrderQuantity = $ingredient->minimum_order_quantity;
        $idealStock = $reorderPoint * 2; // Double the reorder point for safety stock

        $orderQuantity = $idealStock - $currentStock;

        // Ensure we meet minimum order quantity
        if ($orderQuantity < $minimumOrderQuantity) {
            $orderQuantity = $minimumOrderQuantity;
        }

        return $orderQuantity;
    }

    private function createPurchaseOrder(Ingredient $ingredient, float $quantity): PurchaseOrder
    {
        return DB::transaction(function () use ($ingredient, $quantity) {
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $ingredient->supplier_info['id'] ?? null,
                'status' => 'pending',
                'order_date' => now(),
                'expected_delivery_date' => now()->addDays($ingredient->lead_time_days),
                'notes' => "Auto-generated purchase order for low stock item: {$ingredient->name}",
            ]);

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'ingredient_id' => $ingredient->id,
                'quantity' => $quantity,
                'unit_price' => $ingredient->cost,
                'total_price' => $quantity * $ingredient->cost,
            ]);

            return $purchaseOrder;
        });
    }
}
