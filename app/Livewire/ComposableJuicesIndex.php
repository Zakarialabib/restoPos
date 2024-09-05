<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ComposableJuice;
use App\Models\InventoryAlert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.guest')]
#[Title('Composable Juices')]
class ComposableJuicesIndex extends Component
{
    #[Url('step', 'keep')]
    public $step = 1;
    public $selectedFruits = [];
    public $selectedBase;
    public $selectedSugar;
    public $selectedAddons = [];
    public $cart = [];
    public $totalPrice = 0;

    public $bases = ['Water', 'Orange Juice', 'Milk'];
    public $sugars = ['No Sugar', 'Light', 'Medium', 'Sweet'];
    public $addons = ['Dried Fruits', 'Nuts', 'Seeds'];

    public $customerName;
    public $customerPhone;
    public $showSuccess = false;
    public $order;

    public $search = '';

    public $showCheckout = false;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    #[Computed]
    public function fruits()
    {
        return Product::where('is_composable', true)
            ->where('stock', '>', 0)
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function boot()
    {
        session()->forget('cart');
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function toggleFruit($fruitId)
    {
        $fruit = Product::find($fruitId);

        if (!$fruit) {
            $this->addError('invalidFruit', "The selected fruit is not available.");
            return;
        }

        if (in_array($fruitId, $this->selectedFruits)) {
            $this->selectedFruits = array_diff($this->selectedFruits, [$fruitId]);
        } else {
            $this->selectedFruits[] = $fruitId;
        }
    }

    public function calculatePrice()
    {
        $this->totalPrice = 0;
        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Product::find($fruitId);
            if ($fruit) {
                $this->totalPrice += $fruit->price;
            }
        }
        // Add price calculations for base, sugar, and add-ons
        // This is just a placeholder, adjust according to your pricing logic
        $this->totalPrice += 5; // Base price
        if ($this->selectedSugar !== 'No Sugar') {
            $this->totalPrice += 2; // Sugar price
        }
        $this->totalPrice += count($this->selectedAddons) * 3; // Addons price
    }

    public function toggleAddon($addon)
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
        return array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function toggleCheckout()
    {
        $this->showCheckout = !$this->showCheckout;
    }

    public function addToCart()
    {
        $this->calculatePrice();

        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Product::find($fruitId);
            if (!$fruit || $fruit->stock <= 0) {
                $this->addError('outOfStock', "{$fruit->name} is out of stock.");
                return;
            }
        }

        $this->cart[] = [
            'name' => 'Custom Juice',
            'price' => $this->totalPrice,
            'quantity' => 1,
            'ingredients' => [
                'fruits' => Product::whereIn('id', $this->selectedFruits)->pluck('name')->toArray(),
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'addons' => $this->selectedAddons,
            ],
        ];
        session()->put('cart', $this->cart);

        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Product::find($fruitId);
            $fruit->decrement('stock');
            if ($fruit->isLowStock()) {
                InventoryAlert::create([
                    'product_id' => $fruit->id,
                    'message' => "Low stock alert for {$fruit->name}",
                ]);
            }
        }
        $this->step = 1;
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
        $this->reset(['selectedFruits', 'selectedBase', 'selectedSugar', 'selectedAddons']);
    }

    public function render()
    {
        $composableJuices = ComposableJuice::all();
        return view('livewire.composable-juices-index', ['composableJuices' => $composableJuices]);
    }

    public function placeOrder()
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

    public function close()
    {
        $this->showSuccess = false;
        $this->reset([
            'cart',
            'customerName',
            'customerPhone',
            'showCheckout',
            'selectedFruits',
            'selectedBase',
            'selectedSugar',
            'selectedAddons'
        ]);
        session()->forget('cart');
    }
}