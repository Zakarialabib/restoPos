<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Finance\Purchases;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\InventoryService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Purchase Management')]
#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public array $stats = [];
    public ?string $selectedOrder = null;
    public ?string $status_filter = '';
    public ?string $supplier_filter = '';
    public ?string $date_from = '';
    public ?string $date_to = '';
    public ?string $search = '';

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->stats = [
            'total_orders' => PurchaseOrder::count(),
            'pending_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'processing_orders' => PurchaseOrder::where('status', 'processing')->count(),
            'completed_orders' => PurchaseOrder::where('status', 'completed')->count(),
            'total_amount' => PurchaseOrder::where('status', 'completed')->sum('total_amount'),
            'average_order_value' => PurchaseOrder::where('status', 'completed')->avg('total_amount'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.finance.purchases.index', [
            'orders' => PurchaseOrder::with(['supplier', 'items.ingredient'])
                ->when($this->status_filter, function ($query): void {
                    $query->where('status', $this->status_filter);
                })
                ->when($this->supplier_filter, function ($query): void {
                    $query->where('supplier_id', $this->supplier_filter);
                })
                ->when($this->date_from, function ($query): void {
                    $query->whereDate('created_at', '>=', $this->date_from);
                })
                ->when($this->date_to, function ($query): void {
                    $query->whereDate('created_at', '<=', $this->date_to);
                })
                ->when($this->search, function ($query): void {
                    $query->where(function ($q): void {
                        $q->where('reference', 'like', '%' . $this->search . '%')
                            ->orWhereHas('supplier', function ($q): void {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->latest()
                ->paginate(10),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function approveOrder(string $orderId): void
    {
        $inventoryService = app(InventoryService::class);
        $inventoryService->approvePurchaseOrder($orderId);
        $this->loadStats();
    }

    public function receiveOrder(string $orderId): void
    {
        $inventoryService = app(InventoryService::class);
        $inventoryService->receivePurchaseOrder($orderId);
        $this->loadStats();
    }

    public function cancelOrder(string $orderId): void
    {
        $inventoryService = app(InventoryService::class);
        $inventoryService->cancelPurchaseOrder($orderId);
        $this->loadStats();
    }

    public function getListeners(): array
    {
        return [
            'purchase-order-created' => 'loadStats',
            'purchase-order-updated' => 'loadStats',
        ];
    }
}
