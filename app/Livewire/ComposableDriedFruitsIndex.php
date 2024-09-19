<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\ComposableDriedFruit;
use App\Models\InventoryAlert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Composable Dried Fruits')]
class ComposableDriedFruitsIndex extends Component
{
    #[Url('step', 'keep')]
    public $step = 1;
    public $selectedDriedFruits = [];
    public $selectedAddons = [];
    public $cart = [];
    public $totalPrice = 0;

    public $addons = ['Box', 'Gift Box', 'Bag'];

    public $customerName;
    public $customerPhone;
    public $showSuccess = false;
    public $order;

    public $search = '';

    public $showCheckout = false;

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    #[Computed]
    public function driedFruits()
    {
        return Product::where('category_id', Category::where('name', 'Dried Fruits')->first()->id)
            ->where('stock', '>', 0)
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function boot(): void
    {
        session()->forget('cart');
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function toggleDriedFruit($driedFruitId): void
    {
        $driedFruit = Product::find($driedFruitId);

        if (!$driedFruit) {
            $this->addError('invalidDriedFruit', "The selected dried fruit is not available.");
            return;
        }

        if (in_array($driedFruitId, $this->selectedDriedFruits)) {
            $this->selectedDriedFruits = array_diff($this->selectedDriedFruits, [$driedFruitId]);
        } else {
            $this->selectedDriedFruits[] = $driedFruitId;
        }
    }

    public function calculatePrice(): void
    {
        $this->totalPrice = 0;
        foreach ($this->selectedDriedFruits as $driedFruitId) {
            $driedFruit = Product::find($driedFruitId);
            if ($driedFruit) {
                $this->totalPrice += $driedFruit->price;
            }
        }
        $this->totalPrice += count($this->selectedAddons) * 3; // Addons price
    }

    public function toggleAddon($addon): void
    {
        if (in_array($addon, $this->selectedAddons)) {
            $this->selectedAddons = array_diff($this->selectedAddons, [$addon]);
        } else {
            $this->selectedAddons[] = $addon;
        }
    }

    #[Computed]
    public function cartTotal()
    {
        return array_reduce($this->cart, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
    }

    public function toggleCheckout(): void
    {
        $this->showCheckout = ! $this->showCheckout;
    }

    public function addToCart(): void
    {
        $this->calculatePrice();

        foreach ($this->selectedDriedFruits as $driedFruitId) {
            $driedFruit = Product::find($driedFruitId);
            if (!$driedFruit || $driedFruit->stock <= 0) {
                $this->addError('outOfStock', "{$driedFruit->name} is out of stock.");
                return;
            }
        }

        $this->cart[] = [
            'name' => 'Custom Dried Fruits',
            'price' => $this->totalPrice,
            'quantity' => 1,
            'ingredients' => [
                'driedFruits' => Product::whereIn('id', $this->selectedDriedFruits)->pluck('name')->toArray(),
                'addons' => $this->selectedAddons,
            ],
        ];
        session()->put('cart', $this->cart);

        foreach ($this->selectedDriedFruits as $driedFruitId) {
            $driedFruit = Product::find($driedFruitId);
            $driedFruit->decrement('stock');
            if ($driedFruit->isLowStock()) {
                InventoryAlert::create([
                    'product_id' => $driedFruit->id,
                    'message' => "Low stock alert for {$driedFruit->name}",
                ]);
            }
        }
        $this->resetSelections();
    }

    public function resetSelections(): void
    {
        $this->selectedDriedFruits = [];
        $this->selectedAddons = [];
        $this->step = 1;
    }

    public function removeFromCart($index): void
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
        $this->resetSelections();
    }

    public function render()
    {
        $composableDriedFruits = ComposableDriedFruit::all();
        return view('livewire.composable-dried-fruits-index', ['composableDriedFruits' => $composableDriedFruits]);
    }

    public function placeOrder(): void
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:255',
        ]);

        $order = Order::create([
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'total_amount' => $this->cartTotal,
            'status' => 'pending',
        ]);

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'details' => $item['ingredients'],
            ]);
        }

        $this->reset(['cart', 'customerName', 'customerPhone', 'showCheckout']);
        session()->forget('cart');

        $this->showSuccess = true;
        $this->order = $order;
    }

    public function close(): void
    {
        $this->showSuccess = false;
        $this->resetSelections();
        session()->forget('cart');
    }
}
