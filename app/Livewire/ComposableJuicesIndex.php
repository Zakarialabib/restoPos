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
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public $customJuiceName;

    // public $cartTotal;
    public $search = '';


    #[Computed]
    public function cart()
    {
        return session()->get('cart', []);
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
        return Composable::with('ingredients')->get();
    }

    // public function getJuiceIngredients($recipeId)
    // {
    //     $recipe = Product::find($recipeId);
    //     return $recipe->ingredients()->get();
    // }

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
            $this->addError('fruitLimit', __("You can only select up to 5 fruits."));
            return;
        }

        $fruit = Ingredient::find($fruitId);

        if ( ! $fruit) {
            $this->addError('invalidFruit', __("The selected fruit is not available."));
            return;
        }

        if (in_array($fruitId, $this->selectedFruits)) {
            $this->selectedFruits = array_diff($this->selectedFruits, [$fruitId]);
        } else {
            $this->selectedFruits[] = $fruitId;
        }

        $this->calculatePrice();
    }

    public function calculatePrice(): void
    {
        $this->totalPrice = 0;
        $fruitCount = count($this->selectedFruits);

        // Base portion calculation remains the same...
        $basePortionSize = match ($fruitCount) {
            1 => 0.6,
            2 => 0.5,
            3 => 0.4,
            4 => 0.3,
            5 => 0.2,
            default => 0.6
        };

        $fruitPortionSize = (1 - $basePortionSize) / ($fruitCount ?: 1);

        // Calculate fruits price using current prices
        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Ingredient::find($fruitId);
            if ($fruit) {
                $currentPrice = $fruit->getCurrentPrice()?->price ?? $fruit->price;
                $portionPrice = $currentPrice * $fruitPortionSize;
                $this->totalPrice += $portionPrice;
            }
        }

        // Calculate base price
        if ($this->selectedBase) {
            $baseIngredient = Ingredient::where('name', $this->selectedBase)->first();
            if ($baseIngredient) {
                $currentPrice = $baseIngredient->getCurrentPrice()?->price ?? $baseIngredient->price;
                $basePrice = $currentPrice * $basePortionSize;
                $this->totalPrice += $basePrice;
            }
        }

        // Add-ons calculation
        foreach ($this->selectedAddons as $addon) {
            $addonIngredient = Ingredient::where('name', $addon)->first();
            if ($addonIngredient) {
                $currentPrice = $addonIngredient->getCurrentPrice()?->price ?? $addonIngredient->price;
                $addonPortionSize = 0.1;
                $addonPrice = $currentPrice * $addonPortionSize;
                $this->totalPrice += $addonPrice;
            }
        }

        $this->totalPrice = round($this->totalPrice, 2);
    }

    public function validateIngredients(): bool
    {
        foreach ($this->selectedFruits as $fruitId) {
            $fruit = Ingredient::find($fruitId);
            if ( ! $fruit || ! $fruit->hasEnoughStock(1)) {
                $this->addError('stock', __('Some ingredients are out of stock.'));
                return false;
            }
        }

        if ($this->selectedBase) {
            $base = Ingredient::where('name', $this->selectedBase)->first();
            if ( ! $base || ! $base->hasEnoughStock(1)) {
                $this->addError('stock', __('Base ingredient is out of stock.'));
                return false;
            }
        }

        // Similar checks for sugar and addons
        return true;
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

    public function addToCart(): void
    {
        if (empty($this->selectedFruits)) {
            $this->addError('emptySelection', __("Please select at least one fruit for your juice."));
            return;
        }

        $this->validate([
            'selectedFruits' => 'required|array|min:1',
            'selectedBase' => 'required|string',
            'selectedSugar' => 'required|string',
        ]);

        try {
            // Create unique name for the custom juice
            $fruitNames = Ingredient::whereIn('id', $this->selectedFruits)
                ->pluck('name')
                ->join(', ');

            $this->customJuiceName = __("Custom Juice") . " ({$fruitNames})";

            // Calculate final price if not already calculated
            $this->calculatePrice();

            // Prepare ingredients list with portions
            $ingredients = [
                'fruits' => Ingredient::whereIn('id', $this->selectedFruits)
                    ->get()
                    ->map(fn ($fruit) => [
                        'name' => $fruit->name,
                        'portion' => $this->calculateIngredientPortion('fruit'),
                        'price' => $fruit->price * $this->calculateIngredientPortion('fruit')
                    ])
                    ->toArray(),
                'base' => [
                    'name' => $this->selectedBase,
                    'portion' => $this->calculateIngredientPortion('base'),
                    'price' => optional(Ingredient::where('name', $this->selectedBase)->first())
                        ?->price * $this->calculateIngredientPortion('base') ?? 0
                ],
                'sugar' => [
                    'name' => $this->selectedSugar,
                    'portion' => $this->calculateIngredientPortion('sugar'),
                    'price' => optional(Ingredient::where('name', $this->selectedSugar)->first())
                        ?->price * $this->calculateIngredientPortion('sugar') ?? 0
                ],
                'addons' => collect($this->selectedAddons)
                    ->map(fn ($addon) => [
                        'name' => $addon,
                        'portion' => $this->calculateIngredientPortion('addon'),
                        'price' => optional(Ingredient::where('name', $addon)->first())
                            ?->price * $this->calculateIngredientPortion('addon') ?? 0
                    ])
                    ->toArray(),
            ];

            // Add to cart with detailed information
            $this->cart[] = [
                'name' => $this->customJuiceName,
                'price' => $this->totalPrice,
                'quantity' => 1,
                'ingredients' => $ingredients,
                'type' => 'custom_juice',
                'created_at' => now()->toDateTimeString(),
            ];

            // Update session
            session()->put('cart', $this->cart);

            // Reset selections
            $this->reset([
                'selectedFruits',
                'selectedBase',
                'selectedSugar',
                'selectedAddons',
                'totalPrice'
            ]);

            // Return to first step
            $this->step = 1;

            session()->flash('success', __('Custom juice added to cart successfully!'));
        } catch (Exception $e) {
            $this->addError('cart', __('Error adding juice to cart. Please try again.'));
            Log::error('Error adding juice to cart: ' . $e->getMessage());
        }
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
        if (empty($this->cart)) {
            $this->addError('cart', __('Your cart is empty.'));
            return;
        }

        try {
            DB::transaction(function (): void {
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
                        'details' => $item['ingredients'],
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
                $this->order = $order;
            });
        } catch (Exception $e) {
            // return exception message
            dd($e->getMessage());
            // $this->addError('order', __('Error processing order. Please try again.'));
            // Log::error('Error processing order: ' . $e->getMessage());
        }
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
        return Composable::with('ingredients')->get();
    }

    // Update the helper method to use dynamic portions
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
            default => 0.0
        };
    }
}
