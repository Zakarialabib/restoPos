<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Composable;
use App\Models\Ingredient;
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

    public $customerName;
    public $customerPhone;
    public $showSuccess = false;
    public $order;
    public $recipes;

    public $search = '';

    public $showCheckout = false;

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    #[Computed]
    public function steps()
    {
        return [__('Select Fruits'), __('Choose Base'), __('Sugar Preference'), __('Add-ons')];
    }

    #[Computed]
    public function bases(): array
    {
        return [
            __('Water'),
            __('Orange Juice'),
            __('Milk'),
        ];
    }

    #[Computed]
    public function sugars(): array
    {
        return [
            __('No Sugar'),
            __('Light'),
            __('Medium'),
            __('Sweet'),
        ];
    }

    #[Computed]
    public function addons(): array
    {
        return [
            __('Dried Fruits'),
            __('Nuts'),
            __('Seeds'),
        ];
    }

    #[Computed]
    public function fruits()
    {
        return Product::where('is_composable', true)
            ->where('category_id', Category::where('name', 'Fruits')->first()->id)
            ->where('stock', '>', 0)
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }

    #[Computed]
    public function popularJuices()
    {
        return Composable::with('products', 'ingredients')->get();
    }

    public function getJuiceIngredients($recipeId)
    {
        $recipe = Product::find($recipeId);
        return $recipe->ingredients()->get();
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function toggleFruit($fruitId): void
    {
        if (count($this->selectedFruits) >= 5 && !in_array($fruitId, $this->selectedFruits)) {
            $this->addError('fruitLimit', "You can only select up to 5 fruits.");
            return;
        }

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

    public function calculatePrice(): void
    {
        $this->totalPrice = 0;
        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Product::find($fruitId);
            if ($fruit) {
                $this->totalPrice += $fruit->price;
            }
        }
        $this->totalPrice += 5; // Base price
        if ($this->selectedSugar && $this->selectedSugar !== 'No Sugar') {
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
        return array_reduce($this->cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
    }

    public function toggleCheckout(): void
    {
        $this->showCheckout = !$this->showCheckout;
    }

    public function addToCart(): void
    {
        $this->calculatePrice();

        $this->validate([
            'selectedFruits' => 'required|array|min:1',
            'selectedBase' => 'required|string',
            'selectedSugar' => 'required|string',
            'selectedAddons' => 'array',
        ]);

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

    public function removeFromCart($index): void
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
        $this->reset(['selectedFruits', 'selectedBase', 'selectedSugar', 'selectedAddons']);
    }

    public function render()
    {
        $composableJuices = Composable::all();
        return view('livewire.composable-juices-index', ['composableJuices' => $composableJuices]);
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
            'status' => OrderStatus::Pending,
        ]);
        $orderItems = [];
        foreach ($this->cart as $item) {
            $orderItem = OrderItem::create([
                'name' => $item['name'],
                'order_id' => $order->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'details' => $item['ingredients'],
            ]);
            $orderItems[] = $orderItem;
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
            'selectedFruits',
            'selectedBase',
            'selectedSugar',
            'selectedAddons'
        ]);
        session()->forget('cart');
    }

    #[Computed]
    public function composables()
    {
        return Composable::with('products', 'ingredients')->get();
    }
}
