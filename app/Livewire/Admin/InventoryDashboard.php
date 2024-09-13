<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Ingredient;
use App\Models\InventoryAlert;
use App\Models\Product;
use Livewire\Component;

class InventoryDashboard extends Component
{
    public $lowStockIngredients;
    public $lowStockProducts;
    public $recentAlerts;
    public function mount(): void
    {
        $this->loadInventoryData();
    }

    public function loadInventoryData(): void
    {
        $this->lowStockProducts = Product::where('stock', '<=', 'low_stock_threshold')->get();
        $this->lowStockIngredients = Ingredient::where('quantity', '<=', 'reorder_level')->get();
        $this->recentAlerts = InventoryAlert::latest()->take(5)->get();
    }

    public function resolveAlert($alertId): void
    {
        $alert = InventoryAlert::find($alertId);
        if ($alert) {
            $alert->delete();
            $this->loadInventoryData();
        }
    }

    public function render()
    {
        return view('livewire.admin.inventory-dashboard');
    }
}
