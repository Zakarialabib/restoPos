<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Ingredient;
use App\Models\InventoryAlert;
use App\Models\Order;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class InventoryDashboard extends Component
{
    public $dateRange = 'today';
    public $startDate;
    public $endDate;
    public $selectedCategory;
    public $expiringIngredients;
    public $recentAlerts;

    public function mount(): void
    {
        $this->startDate = now()->startOfDay();
        $this->endDate = now()->endOfDay();
    }

    #[Computed]
    public function lowStockIngredients()
    {
        return Ingredient::lowStock()->get();
    }

    #[Computed]
    public function expiringIngredients()
    {
        return Ingredient::expiringSoon(7)->get();
    }

    #[Computed]
    public function recentAlerts()
    {
        return InventoryAlert::unresolved()
            ->with('product')
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function inventoryStats()
    {
        return [
            'total_products' => Product::count(),
            'expiring_soon' => $this->expiringIngredients->count(),
            'alerts_count' => $this->recentAlerts->count(),
        ];
    }


    #[Computed]
    public function salesStats()
    {
        return [
            'total_sales' => Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->sum('total_amount'),
            'orders_count' => Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->count(),
            'average_order_value' => Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->avg('total_amount') ?? 0,
        ];
    }

    public function setDateRange(string $range): void
    {
        $this->dateRange = $range;

        match ($range) {
            'today' => [
                $this->startDate = now()->startOfDay(),
                $this->endDate = now()->endOfDay(),
            ],
            'week' => [
                $this->startDate = now()->startOfWeek(),
                $this->endDate = now()->endOfWeek(),
            ],
            'month' => [
                $this->startDate = now()->startOfMonth(),
                $this->endDate = now()->endOfMonth(),
            ],
            'year' => [
                $this->startDate = now()->startOfYear(),
                $this->endDate = now()->endOfYear(),
            ],
            default => null,
        };
    }

    public function resolveAlert(int $alertId): void
    {
        $alert = InventoryAlert::find($alertId);
        if ($alert) {
            $alert->resolve();
        }
    }

    public function render()
    {
        return view('livewire.admin.inventory-dashboard');
    }


    // Add method for expiry tracking
    public function getExpiryTracking()
    {
        return $this->expiringIngredients
            ->groupBy(fn ($ingredient) => $ingredient->expiry_date->format('Y-m-d'))
            ->map(fn ($group) => [
                'count' => $group->count(),
                'value' => $group->sum('stock')
            ]);
    }
}
