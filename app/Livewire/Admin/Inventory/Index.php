<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Inventory;

use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\Supplier;
use App\Services\InventoryManagementService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class Index extends Component
{
    use WithPagination;

    public array $lowStockItems = [];
    public array $purchaseOrderItems = [];
    public ?string $selectedSupplier = null;
    public ?string $notes = null;
    public bool $showPurchaseOrderModal = false;
    public array $stats = [];

    public function mount(InventoryManagementService $inventoryService): void
    {
        $this->lowStockItems = $inventoryService->checkLowStockIngredients()->toArray();
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->stats = [
            'total_ingredients' => Ingredient::count(),
            'low_stock_count' => Stock::whereRaw('quantity <= reorder_point')->count(),
            'out_of_stock_count' => Stock::whereRaw('quantity <= minimum_quantity')->count(),
            'pending_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'approved_orders' => PurchaseOrder::where('status', 'approved')->count(),
            'received_orders' => PurchaseOrder::where('status', 'received')->count(),
            'total_purchase_amount' => PurchaseOrder::where('status', '!=', 'cancelled')->sum('total_amount'),
            'monthly_purchase_amount' => PurchaseOrder::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.inventory.index', [
            'stocks' => Stock::with('ingredient')
                ->orderBy('quantity')
                ->paginate(10),
            'suppliers' => Supplier::all(),
            'recent_purchase_orders' => PurchaseOrder::with(['supplier', 'items.ingredient'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    public function addToPurchaseOrder(string $ingredientId): void
    {
        $ingredient = Ingredient::findOrFail($ingredientId);
        $stock = Stock::where('ingredient_id', $ingredientId)->first();

        $this->purchaseOrderItems[] = [
            'ingredient_id' => $ingredientId,
            'name' => $ingredient->name,
            'quantity' => $stock->reorder_point * 2,
            'unit_price' => $ingredient->price ?? 0,
            'notes' => null,
        ];

        $this->showPurchaseOrderModal = true;
    }

    public function removeFromPurchaseOrder(int $index): void
    {
        unset($this->purchaseOrderItems[$index]);
        $this->purchaseOrderItems = array_values($this->purchaseOrderItems);

        if (empty($this->purchaseOrderItems)) {
            $this->showPurchaseOrderModal = false;
        }
    }

    public function createPurchaseOrder(InventoryManagementService $inventoryService): void
    {
        $this->validate([
            'selectedSupplier' => 'required|exists:suppliers,id',
            'purchaseOrderItems' => 'required|array|min:1',
            'purchaseOrderItems.*.quantity' => 'required|numeric|min:0.01',
            'purchaseOrderItems.*.unit_price' => 'required|numeric|min:0',
        ]);

        $userId = Auth::id();
        if ( ! $userId) {
            throw new RuntimeException('User must be authenticated to create purchase orders');
        }

        $inventoryService->createPurchaseOrder(
            $this->purchaseOrderItems,
            (int) $this->selectedSupplier,
            $userId,
            $this->notes
        );

        $this->reset(['purchaseOrderItems', 'selectedSupplier', 'notes', 'showPurchaseOrderModal']);
        $this->lowStockItems = $inventoryService->checkLowStockIngredients()->toArray();
        $this->loadStats();
        $this->dispatch('purchase-order-created');
    }

    public function receivePurchaseOrder($purchaseOrderId, InventoryManagementService $inventoryService): void
    {
        $userId = Auth::id();
        if ( ! $userId) {
            throw new RuntimeException('User must be authenticated to receive purchase orders');
        }

        $inventoryService->receivePurchaseOrder((int) $purchaseOrderId, $userId);
        $this->lowStockItems = $inventoryService->checkLowStockIngredients()->toArray();
        $this->loadStats();
        $this->dispatch('purchase-order-received');
    }

    public function approvePurchaseOrder($purchaseOrderId): void
    {
        $userId = Auth::id();
        if ( ! $userId) {
            throw new RuntimeException('User must be authenticated to approve purchase orders');
        }

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);

        $this->loadStats();
        $this->dispatch('purchase-order-approved');
    }
}
