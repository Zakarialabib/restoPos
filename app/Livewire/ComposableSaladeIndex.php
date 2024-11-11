<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Composable;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Composable Salade')]
class ComposableSaladeIndex extends Component
{
    #[Url('step', 'keep')]
    public $step = 1;
    public $selectedSalade = [];
    public $selectedBase;
    public $selectedSugar;
    public $selectedAddons = [];
    public $cart = [];
    public $totalPrice = 0;

    public $cartTotal;
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
    public function steps()
    {
        return [__('Select Salade'), __('Choose Base'), __('Sugar Preference'), __('Add-ons')];
    }

    #[Computed]
    public function bases(): array
    {
        return [
            __('Milk'),
            __('Cream'),
            __('Almond Milk'),
            __('Coconut Milk'),
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
            __('Honey'),
            __('Cream'),
            __('Caramel'),
            __('Vanilla'),
            __('Chocolate'),
        ];
    }

    #[Computed]
    public function salades()
    {
        return Ingredient::where('category_id', Category::where('name', 'Salade')->first()->id)
            ->where('stock', '>', 0)
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }

    #[Computed]
    public function composables()
    {
        return Composable::with('ingredients')->get();
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function toggleSalade($saladeId): void
    {
        $salade = Product::find($saladeId);

        if ( ! $salade) {
            $this->addError('invalidSalade', "The selected salade is not available.");
            return;
        }

        if (in_array($saladeId, $this->selectedSalade)) {
            $this->selectedSalade = array_diff($this->selectedSalade, [$saladeId]);
        } else {
            $this->selectedSalade[] = $saladeId;
        }
    }

    public function calculatePrice(): void
    {
        $this->totalPrice = 0;
        foreach ($this->selectedSalade as $saladeId) {
            $salade = Product::find($saladeId);
            if ($salade) {
                $this->totalPrice += $salade->price;
            }
        }
        $this->totalPrice += 5; // Base price
        if ($this->selectedSugar && 'No Sugar' !== $this->selectedSugar) {
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

        foreach ($this->selectedSalade as $saladeId) {
            $salade = Product::find($saladeId);
            if ( ! $salade || $salade->stock <= 0) {
                $this->addError('outOfStock', "{$salade->name} is out of stock.");
                return;
            }
        }

        $this->cart[] = [
            'name' => 'Custom Salade',
            'price' => $this->totalPrice,
            'quantity' => 1,
            'ingredients' => [
                'salades' => Product::whereIn('id', $this->selectedSalade)->pluck('name')->toArray(),
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'addons' => $this->selectedAddons,
            ],
        ];
        session()->put('cart', $this->cart);

        $this->resetSelections();
    }

    public function resetSelections(): void
    {
        $this->selectedSalade = [];
        $this->selectedBase = null;
        $this->selectedSugar = null;
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
        $composableSalades = Composable::all();
        return view('livewire.composable-salade-index', ['composableSalades' => $composableSalades]);
    }

    public function placeOrder(): void
    {
        // $this->validate([
        //     'customerName' => 'required|string|max:255',
        //     'customerPhone' => 'required|string|max:255',
        // ], [
        //     'customerName.required' => __('Please enter your name.'),
        //     'customerPhone.required' => __('Please enter your phone number.'),
        // ]);

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
