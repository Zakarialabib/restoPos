<?php

declare(strict_types=1);

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class DisplaySystem extends Component
{
    public Collection $activeOrders;
    public Collection $completedOrders;
    public string $selectedCategory = 'all';
    public array $categories = [];
    public int $refreshInterval = 10; // seconds

    public function mount(): void
    {
        $this->loadOrders();
        $this->loadCategories();
    }

    public function loadOrders(): void
    {
        $query = Order::with(['items.product', 'customer'])
            ->where('status', 'in_progress');

        if ('all' !== $this->selectedCategory) {
            $query->whereHas('items.product', function ($q): void {
                $q->where('category', $this->selectedCategory);
            });
        }

        $this->activeOrders = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->completedOrders = Order::with(['items.product'])
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function loadCategories(): void
    {
        $this->categories = ['all' => 'All Categories'] +
            Product::distinct('category')
                ->pluck('category', 'category')
                ->toArray();
    }

    #[On('echo:private-orders,OrderCreated')]
    public function handleNewOrder(): void
    {
        $this->loadOrders();
        $this->dispatch('playNotification', 'New order received');
    }

    public function markAsStarted(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        $this->loadOrders();
        $this->dispatch('orderStatusUpdated', $orderId);
    }

    public function markAsCompleted(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->loadOrders();
        $this->dispatch('orderStatusUpdated', $orderId);
    }

    public function getEstimatedWaitTime(Order $order): int
    {
        // Calculate based on number of items and complexity
        $baseTime = 5; // Base time in minutes
        $itemCount = $order->items->sum('quantity');
        $complexityFactor = $this->calculateComplexityFactor($order);

        return (int) ceil($baseTime * $itemCount * $complexityFactor);
    }

    public function render()
    {
        return view('livewire.kitchen.display-system');
    }

    protected function calculateComplexityFactor(Order $order): float
    {
        // Factor in preparation complexity of items
        $totalComplexity = $order->items->sum(fn ($item) => $item->product->preparation_time ?? 1);

        return $totalComplexity / $order->items->count() ?: 1;
    }
}
