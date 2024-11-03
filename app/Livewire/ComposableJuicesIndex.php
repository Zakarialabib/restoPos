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
use Illuminate\Support\Number;
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

    // public $customerName;
    // public $customerPhone;
    public $showSuccess = false;
    public $order;
    public $recipes;

    public $cartTotal;
    public $search = '';

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    #[Computed]
    public function steps()
    {
        return [
            __('Select Fruits'),
            __('Choose Base'),
            __('Sugar Preference'),
            __('Add-ons'),
            // size 
        ];
    }

    #[Computed]
    public function sizes(): array
    {
        return [
            'small' => [
                'name' => __('Small'),
                'capacity' => '150ml',
            ],
            'medium' => [
                'name' => __('Medium'),
                'capacity' => '250ml',
            ],
            'large' => [
                'name' => __('Large'),
                'capacity' => '500ml',
            ],
        ];
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
        return Ingredient::where('category_id', Category::where('name', 'Fruits')->first()->id)
            ->where('is_composable', true)
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
        // if selected fruit is orange should skip base step  
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function toggleFruit($fruitId): void
    {
        if (count($this->selectedFruits) >= 5 && ! in_array($fruitId, $this->selectedFruits)) {
            $this->addError('fruitLimit', "You can only select up to 5 fruits.");
            return;
        }

        $fruit = Product::find($fruitId);

        if (! $fruit) {
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
            $fruit = Ingredient::find($fruitId);
            if ($fruit) {
                $this->totalPrice += (int) ($fruit->price);
            }
        }
        // addons and size should be calculated as well  
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

    public function addToCart(): void
    {
        // Check if the current item is already in the cart
        if (empty($this->selectedFruits)) {
            $this->addError('emptySelection', __("Please select at least one fruit for your juice."));
            return;
        }

        $this->calculatePrice();

        $this->validate([
            'selectedFruits' => 'required|array|min:1',
            'selectedBase' => 'required|string',
            'selectedSugar' => 'required|string',
            'selectedAddons' => 'array',
        ]);

        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Product::find($fruitId);
            if (! $fruit || $fruit->stock <= 0) {
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
            $fruit = Ingredient::find($fruitId);
            $fruit->decrement('stock');
            if ($fruit->isLowStock()) {
                InventoryAlert::create([
                    'ingredient_id' => $fruit->id,
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
        // $this->validate([
        //     'customerName' => 'required|string|max:255',
        //     'customerPhone' => 'required|string|max:255',
        // ], [
        //     'customerName.required' => __('Please enter your name.'),
        //     'customerPhone.required' => __('Please enter your phone number.'),
        // ]);

        $order = Order::create([
            'customer_name' => 'Shop',
            'customer_phone' => '0000000000',
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

        $this->reset([
            'cart',
            //  'customerName', 'customerPhone',
        ]);
        session()->forget('cart');

        $this->showSuccess = true;
        $this->order = $order;
    }

    public function close(): void
    {
        $this->showSuccess = false;
        $this->reset([
            'cart',
            // 'customerName',
            // 'customerPhone',
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
