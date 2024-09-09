<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\ComposableCoffee;
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
#[Title('Composable Coffees')]
class ComposableCoffeesIndex extends Component
{
    #[Url('step', 'keep')]
    public $step = 1;
    public $selectedCoffees = [];
    public $selectedBase;
    public $selectedSugar;
    public $selectedAddons = [];
    public $cart = [];
    public $totalPrice = 0;

    public $bases = ['Milk', 'Cream', 'Almond Milk', 'Coconut Milk'];
    public $sugars = ['No Sugar', 'Light', 'Medium', 'Sweet'];
    public $addons = ['Honey', 'Cream', 'Caramel', 'Vanilla', 'Chocolate'];

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
    public function coffees()
    {
        return Product::where('category_id', Category::where('name', 'Coffee')->first()->id)
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

    public function toggleCoffee($coffeeId): void
    {
        $coffee = Product::find($coffeeId);

        if ( ! $coffee) {
            $this->addError('invalidCoffee', "The selected coffee is not available.");
            return;
        }

        if (in_array($coffeeId, $this->selectedCoffees)) {
            $this->selectedCoffees = array_diff($this->selectedCoffees, [$coffeeId]);
        } else {
            $this->selectedCoffees[] = $coffeeId;
        }
    }

    public function calculatePrice(): void
    {
        $this->totalPrice = 0;
        foreach ($this->selectedCoffees as $coffeeId) {
            $coffee = Product::find($coffeeId);
            if ($coffee) {
                $this->totalPrice += $coffee->price;
            }
        }
        // Add price calculations for base, sugar, and add-ons
        // This is just a placeholder, adjust according to your pricing logic
        $this->totalPrice += 5; // Base price
        if ('No Sugar' !== $this->selectedSugar) {
            $this->totalPrice += 2; // Sugar price
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

        foreach ($this->selectedCoffees as $coffeeId) {
            $coffee = Product::find($coffeeId);
            if ( ! $coffee || $coffee->stock <= 0) {
                $this->addError('outOfStock', "{$coffee->name} is out of stock.");
                return;
            }
        }

        $this->cart[] = [
            'name' => 'Custom Coffee',
            'price' => $this->totalPrice,
            'quantity' => 1,
            'ingredients' => [
                'coffees' => Product::whereIn('id', $this->selectedCoffees)->pluck('name')->toArray(),
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'addons' => $this->selectedAddons,
            ],
        ];
        session()->put('cart', $this->cart);

        foreach ($this->selectedCoffees as $coffeeId) {
            $coffee = Product::find($coffeeId);
            $coffee->decrement('stock');
            if ($coffee->isLowStock()) {
                InventoryAlert::create([
                    'product_id' => $coffee->id,
                    'message' => "Low stock alert for {$coffee->name}",
                ]);
            }
        }
        $this->step = 1;
    }

    public function removeFromCart($index): void
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
        $this->reset(['selectedCoffees', 'selectedBase', 'selectedSugar', 'selectedAddons']);
    }

    public function render()
    {
        $composableCoffees = ComposableCoffee::all();
        return view('livewire.composable-coffees-index', ['composableCoffees' => $composableCoffees]);
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
        $this->reset([
            'cart',
            'customerName',
            'customerPhone',
            'showCheckout',
            'selectedCoffees',
            'selectedBase',
            'selectedSugar',
            'selectedAddons'
        ]);
        session()->forget('cart');
    }
}
