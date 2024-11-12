<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Composable;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ComposablePriceCalculator;
use App\Traits\ComposableComponent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Composable Juices')]
class ComposableJuicesIndex extends Component
{
    use ComposableComponent;

    public $selectedFruits = [];
    public $selectedBase;
    public $selectedSugar = [];
    public $customJuiceName;
    public $order;
    public $totalPrice = 0;

    #[Computed]
    public function steps()
    {
        return [__('Select Fruits'), __('Base'), __('Sugar'), __('Add-ons'), __('Size')];
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

    #[Computed(cache: true)]
    public function composableJuices()
    {
        return Product::where('type', 'juice')->get();
    }

    #[Computed(cache: true)]
    public function bases()
    {
        return [
            __('Orange juice'),
            __('Milk'),
            __('Lemon juice'),
            __('Water'),
        ];
    }

    #[Computed(cache: true)]
    public function sugars()
    {
        return [
            __('Sugar'),
            __('Honey'),
            __('No sugar'),
        ];
    }

    #[Computed(cache: true)]
    public function addons()
    {
        return [
            __('Dried fruits'),
            __('Nuts'),
            __('Chocolate'),
            __('Mint'),
            __('Ginger'),
            __('Seeds'),
        ];
    }

    public function toggleFruit(Ingredient $fruit): void
    {
        if (in_array($fruit->id, $this->selectedFruits)) {
            $this->selectedFruits = array_diff($this->selectedFruits, [$fruit->id]);
        } else {
            $this->selectedFruits[] = $fruit->id;
        }
    }

    public function toggleSugar(string $sugar): void
    {
        $this->selectedSugar = $sugar;
    }

    public function toggleBase(string $base): void
    {
        $this->selectedBase = $base;
    }

    public function toggleSize(string $size): void
    {
        $this->selectedSize = $size;
    }

    public function addToCart(): void
    {
        if (empty($this->selectedFruits)) {
            session()->flash('error', __("Please select at least one fruit for your juice."));
            return;
        }

        $this->validate([
            'selectedFruits' => 'required|array|min:1',
            'selectedBase' => 'required|string',
            'selectedSugar' => 'required|string',
        ]);

        try {
            $fruitNames = Ingredient::whereIn('id', $this->selectedFruits)
                ->pluck('name')
                ->map(fn($name) => $name . "\n")
                ->join('');

            $this->customJuiceName = __("Custom Juice") . " ({$fruitNames})";

            $ingredients = [
                'fruits' => Ingredient::whereIn('id', $this->selectedFruits)
                    ->get()
                    ->map(fn($fruit) => [
                        'name' => $fruit->name,
                        'portion' => $this->calculateIngredientPortion('fruit'),
                        'price' => $fruit->price * $this->calculateIngredientPortion('fruit')
                    ])
                    ->toArray(),
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'addons' => collect($this->selectedAddons)
                    ->map(fn($addon) => [
                        'name' => $addon,
                        'portion' => $this->calculateIngredientPortion('addon'),
                        'price' => optional(Ingredient::where('name', $addon)->first())
                            ?->price * $this->calculateIngredientPortion('addon') ?? 0
                    ])
                    ->toArray(),
                'size' => $this->selectedSize,
            ];

            $this->cart[] = [
                'type' => 'juice',
                'name' => $this->customJuiceName,
                'price' => $this->totalPrice,
                'quantity' => 1,
                'ingredients' => $ingredients,
                'created_at' => now()->toDateTimeString(),
            ];

            session()->put('cart', $this->cart);

            $this->reset([
                'selectedFruits',
                'selectedBase',
                'selectedSugar',
                'selectedAddons',
                'totalPrice'
            ]);

            $this->step = 1;

            session()->flash('success', __('Custom juice added to cart successfully!'));
        } catch (Exception $e) {
            Log::error('Error adding juice to cart: ' . $e->getMessage());
            session()->flash('error', __('Unable to add item to cart.'));
        }
    }



    public function placeOrder()
    {
        if (empty($this->cart)) {
            session()->flash('error', __('Your cart is empty.'));
            return;
        }

        try {
            // $this->validate([
            //     'customerName' => 'required|string|max:255',
            //     'customerPhone' => 'required|string|max:255',
            // ], [
            //     'customerName.required' => __('Please enter your name.'),
            //     'customerPhone.required' => __('Please enter your phone number.'),
            // ]);

            // Create order
            $order = Order::create([
                'customer_name' => 'Shop',
                'customer_phone' => '0000000000',
                'total_amount' => $this->cartTotal,
                'status' => OrderStatus::Pending,
            ]);

            // Create order items
            foreach ($this->cart as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'details' => json_encode($item['ingredients']),
                ]);

                // Reduce ingredient stock
                if (isset($item['ingredients']['fruits'])) {
                    foreach ($item['ingredients']['fruits'] as $fruitName) {
                        $ingredient = Ingredient::where('name', $fruitName)->first();
                        if ($ingredient) {
                            $ingredient->decrementStock($item['quantity']);
                        }
                    }
                }
            }

            // Clear cart
            $this->cart = [];
            session()->forget('cart');

            // Set success state
            $this->showSuccess = true;

            session()->flash('success', __('Order placed successfully!'));

            $this->order = $order;
        } catch (Exception $e) {
            // return exception message
            session()->flash('error', __('Error processing order. Please try again.'));
            Log::error('Error processing order: ' . $e->getMessage());
        }
    }

    public function close(): void
    {
        $this->showSuccess = false;
        $this->reset([
            'cart',
            'selectedFruits',
            'selectedBase',
            'selectedSugar',
            'selectedAddons'
        ]);
        session()->forget('cart');
    }

    public function resetSelections(): void
    {
        $this->reset(['selectedFruits', 'selectedBase', 'selectedSugar', 'selectedAddons', 'totalPrice']);
    }

    private function calculateIngredientPortion(string $type): float
    {
        $fruitCount = count($this->selectedFruits);

        // Calculate base portion
        $basePortionSize = match ($fruitCount) {
            1 => 0.6,
            2 => 0.5,
            3 => 0.4,
            4 => 0.3,
            5 => 0.2,
            default => 0.6
        };

        // Calculate fruit portion
        $fruitPortionSize = (1 - $basePortionSize) / ($fruitCount ?: 1);

        return match ($type) {
            'fruit' => $fruitPortionSize,
            'base' => $basePortionSize,
            'addon' => 0.1, // Addons remain fixed at 10%
            'sugar' => 0.1, // Sugar remains fixed at 10%
            'size' => 0.1, // Size remains fixed at 10%
            default => 0.0
        };
    }
}
