<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\OrderProcessor;
use App\Traits\ComposableComponent;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Composable Salade')]
class ComposableSaladeIndex extends Component
{
    use ComposableComponent;

    public array $selectedSalade = [];
    public ?string $selectedBase = null;
    public ?string $selectedSugar = null;
    public ?array $order = null;
    // public bool $showSuccess = false;
    public string $customerName = '';
    public string $customerPhone = '';
    public array $composableSalade = [];

    // #[Computed]
    // public function composableSalade()
    // {
    //     // $this->calculatePrice();
    // }

    public function calculatePrice(): void
    {
        if (empty($this->selectedSize)) {
            return;
        }

        $this->totalPrice = $this->priceCalculator->calculate(
            $this->selectedSalade,
            $this->selectedBase,
            $this->selectedAddons,
            $this->selectedSize,
            'salade'
        );
    }

    #[Computed]
    public function bases(): array
    {
        return cache()->remember('salade_bases', now()->addDay(), fn() => [
            __('Milk'),
            __('Cream'),
            __('Almond Milk'),
            __('Coconut Milk'),
        ]);
    }

    #[Computed]
    public function addons(): array
    {
        return cache()->remember('salade_addons', now()->addDay(), fn() => [
            __('Honey'),
            __('Cream'),
            __('Caramel'),
            __('Vanilla'),
            __('Chocolate'),
        ]);
    }

    #[Computed]
    public function salades()
    {
        return cache()->remember(
            "salades_{$this->search}",
            now()->addMinutes(5),
            fn() => Ingredient::where('category_id', Category::where('name', 'Salade')->first()->id)
                ->where('stock', '>', 0)
                ->where('name', 'like', "%{$this->search}%")
                ->get()
        );
    }

    public function toggleSalade($saladeId): void
    {
        try {
            $salade = Product::findOrFail($saladeId);

            if (!$salade->hasEnoughStock(1)) {
                $this->addError('outOfStock', __(':name is out of stock.', ['name' => $salade->name]));
                return;
            }

            if (in_array($saladeId, $this->selectedSalade)) {
                $this->selectedSalade = array_diff($this->selectedSalade, [$saladeId]);
            } else {
                $this->selectedSalade[] = $saladeId;
            }

            $this->calculatePrice();
        } catch (Exception $e) {
            $this->addError('invalidSalade', __('The selected salade is not available.'));
            logger()->error('Error toggling salade:', ['error' => $e->getMessage()]);
        }
    }

    public function addToCart(): void
    {
        try {
            $this->calculatePrice();

            if (!$this->validateStockForCart()) {
                return;
            }

            $this->cart[] = [
                'type' => 'salade',
                'name' => __('Custom Salade'),
                'price' => $this->totalPrice,
                'quantity' => 1,
                'ingredients' => $this->prepareIngredients(),
            ];

            session()->put('cart', $this->cart);
            $this->reset(['selectedSalade', 'selectedBase', 'selectedSugar', 'selectedAddons', 'selectedSize', 'totalPrice']);
            $this->dispatch('cart-updated');
        } catch (Exception $e) {
            $this->addError('cart', __('Unable to add item to cart.'));
            logger()->error('Error adding to cart:', ['error' => $e->getMessage()]);
        }
    }

    public function placeOrder(): void
    {
        $this->validate([
            'customerName' => 'required|string|min:3|max:255',
            'customerPhone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        try {
            $orderProcessor = new OrderProcessor();
            $this->order = $orderProcessor->process($this->cart, $this->customerName, $this->customerPhone);

            $this->reset(['cart', 'customerName', 'customerPhone', 'showCheckout']);
            session()->forget('cart');
            $this->showSuccess = true;
        } catch (Exception $e) {
            $this->addError('order', __('Unable to process your order.'));
            logger()->error('Error processing order:', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.composable-salade-index');
    }

    protected function validateSelection(): bool
    {
        if (empty($this->selectedSalade)) {
            $this->addError('selection', __('Please select at least one salade item.'));
            return false;
        }
        return $this->validateIngredientStock($this->selectedSalade);
    }

    protected function validateStockForCart(): bool
    {
        foreach ($this->selectedSalade as $saladeId) {
            $salade = Product::find($saladeId);
            if (!$salade || !$salade->hasEnoughStock(1)) {
                $this->addError('outOfStock', __(':name is out of stock.', ['name' => $salade?->name ?? 'Item']));
                return false;
            }
        }
        return true;
    }

    protected function prepareIngredients(): array
    {
        return [
            'salades' => Product::whereIn('id', $this->selectedSalade)
                ->pluck('name')
                ->toArray(),
            'base' => $this->selectedBase,
            'sugar' => $this->selectedSugar,
            'addons' => $this->selectedAddons,
            'size' => $this->selectedSize,
        ];
    }
}
