<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryManagementService
{
    // Stock Management Methods
    public function calculateTotalStockMovement(Model $model, string $type): float
    {
        return StockLog::query()
            ->where('stockable_id', $model->id)
            ->where('stockable_type', get_class($model))
            ->where('type', $type)
            ->sum('amount');
    }

    public function adjustStock(Model $model, float $amount, string $type, ?string $reason = null): StockLog
    {
        return DB::transaction(function () use ($model, $amount, $type, $reason) {
            $stockLog = StockLog::create([
                'stockable_id' => $model->id,
                'stockable_type' => get_class($model),
                'amount' => $amount,
                'type' => $type,
                'reason' => $reason,
                'user_id' => Auth::id()
            ]);

            $model->updateQuietly([
                'stock_quantity' => $this->calculateTotalStockMovement($model, $type)
            ]);

            $this->updateStockStatus($model);

            return $stockLog;
        });
    }

    public function updateStockStatus(Model $model): void
    {
        if ($model instanceof Product) {
            $wasRecentlyActive = $model->status;

            if ($model->stock_quantity <= 0) {
                $model->status = false;
            } elseif ($model->stock_quantity > 0 && ! $wasRecentlyActive) {
                $model->status = true;
            }

            if ($model->isDirty('status')) {
                $model->save();
            }
        }
    }

    // Product Stock Methods
    public function checkStockAvailability(Model $model, ?float $quantity = null): bool
    {
        if ($model instanceof Product) {
            return $this->checkProductStockAvailability($model, $quantity);
        }

        if ($model instanceof Ingredient) {
            return $this->checkIngredientStockAvailability($model, $quantity);
        }

        throw new InvalidArgumentException('Model must be either Product or Ingredient');
    }

    public function getLowStockProducts(?string $categoryId = null): Collection
    {
        return Product::query()
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->get();
    }

    public function getOutOfStockProducts(?string $categoryId = null): Collection
    {
        return Product::query()
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->where('stock_quantity', '<=', 0)
            ->get();
    }

    public function getStockAlerts(?string $categoryId = null): Collection
    {
        return Product::query()
            ->where(function ($query): void {
                $query->where('stock_quantity', '<=', DB::raw('reorder_point'))
                    ->orWhere('stock_quantity', 0);
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'current_stock' => $product->stock_quantity,
                'reorder_point' => $product->reorder_point,
                'status' => 0 === $product->stock_quantity ? 'out_of_stock' : 'low_stock',
            ]);
    }

    public function bulkAdjustStock(array $products, float $quantity, string $reason): void
    {
        DB::transaction(function () use ($products, $quantity, $reason): void {
            foreach ($products as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $this->adjustStock($product, $quantity, $reason);
                }
            }
        });
    }

    // Ingredient Stock Methods
    public function updateIngredientStock(Ingredient $ingredient, float $quantity, string $type, ?string $notes = null): Stock
    {
        return DB::transaction(function () use ($ingredient, $quantity, $type, $notes) {
            $stock = Stock::firstOrCreate(
                ['ingredient_id' => $ingredient->id],
                [
                    'quantity' => 0,
                    'unit' => $ingredient->unit ?? 'kg',
                    'minimum_quantity' => 5,
                    'reorder_point' => 10,
                ]
            );

            $oldQuantity = $stock->quantity;
            $newQuantity = 'addition' === $type ? $oldQuantity + $quantity : $oldQuantity - $quantity;

            if ($newQuantity < 0) {
                throw new Exception("Insufficient stock for ingredient {$ingredient->name}");
            }

            $stock->update([
                'quantity' => $newQuantity,
                'last_checked_at' => now(),
            ]);

            StockLog::create([
                'stock_id' => $stock->id,
                'type' => $type,
                'quantity' => $quantity,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'notes' => $notes,
            ]);

            return $stock;
        });
    }

    public function checkLowStockIngredients(?string $categoryId = null): Collection
    {
        return Ingredient::query()
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->get();
    }

    public function getOutOfStockIngredients(?string $categoryId = null): Collection
    {
        return Ingredient::query()
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->where('stock_quantity', '<=', 0)
            ->get();
    }

    // Purchase Order Methods
    public function createPurchaseOrder(array $items, int $supplierId, int $createdBy, ?string $notes = null): array
    {
        return DB::transaction(function () use ($items, $supplierId, $createdBy, $notes) {
            $totalAmount = 0;
            $purchaseOrderItems = [];

            foreach ($items as $item) {
                $ingredient = Ingredient::findOrFail($item['ingredient_id']);
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $totalPrice = $quantity * $unitPrice;

                $purchaseOrderItems[] = [
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'] ?? null,
                ];

                $totalAmount += $totalPrice;
            }

            $purchaseOrder = \App\Models\PurchaseOrder::create([
                'supplier_id' => $supplierId,
                'status' => 'pending',
                'order_date' => now(),
                'expected_delivery_date' => now()->addDays(7),
                'total_amount' => $totalAmount,
                'notes' => $notes,
                'created_by' => $createdBy,
            ]);

            foreach ($purchaseOrderItems as $item) {
                $purchaseOrder->items()->create($item);
            }

            return [
                'purchase_order' => $purchaseOrder,
                'items' => $purchaseOrder->items,
            ];
        });
    }

    public function receivePurchaseOrder(int $purchaseOrderId, int $approvedBy): void
    {
        DB::transaction(function () use ($purchaseOrderId, $approvedBy): void {
            $purchaseOrder = \App\Models\PurchaseOrder::findOrFail($purchaseOrderId);

            if ('approved' !== $purchaseOrder->status) {
                throw new Exception('Purchase order must be approved before receiving');
            }

            foreach ($purchaseOrder->items as $item) {
                $this->updateIngredientStock(
                    $item->ingredient,
                    $item->quantity,
                    'addition',
                    "Received from purchase order #{$purchaseOrderId}"
                );
            }

            $purchaseOrder->update([
                'status' => 'received',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);
        });
    }

    // Reorder Point Management
    public function updateReorderPoint(Model $model, float $reorderPoint): void
    {
        if ($reorderPoint < 0) {
            throw new Exception(__('Reorder point cannot be negative.'));
        }

        $model->update(['reorder_point' => $reorderPoint]);
    }

    protected function checkProductStockAvailability(Product $product, ?float $quantity = null): bool
    {
        $requiredQuantity = $quantity ?? 1;
        return $product->stock_quantity >= $requiredQuantity;
    }

    protected function checkIngredientStockAvailability(Ingredient $ingredient, ?float $quantity = null): bool
    {
        $requiredQuantity = $quantity ?? 1;
        return $ingredient->stock_quantity >= $requiredQuantity;
    }
}
