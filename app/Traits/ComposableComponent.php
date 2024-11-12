<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Composable;
use App\Services\ComposablePriceCalculator;
use Livewire\Attributes\Computed;

trait ComposableComponent
{
    use HasSizes;

    public $step = 1;
    public $selectedAddons = [];
    public $totalPrice = 0;
    public $cart = [];
    public $search = '';
    public $showSuccess = false;

    protected ComposablePriceCalculator $priceCalculator;

    public function nextStep(): void
    {
        $this->step++;
        // $this->calculatePrice();
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    #[Computed]
    public function cartTotal(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function removeFromCart($index): void
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
        $this->resetSelections();
    }

    public function toggleAddon(string $addon): void
    {
        if (in_array($addon, $this->selectedAddons)) {
            $this->selectedAddons = array_diff($this->selectedAddons, [$addon]);
        } else {
            $this->selectedAddons[] = $addon;
        }
        // $this->calculatePrice();
    }

    protected function validateIngredientStock(array $ingredients): bool
    {
        foreach ($ingredients as $ingredient) {
            if (!$ingredient || !$ingredient->stock > 0) {
                $this->addError('outOfStock', __(':name is out of stock.', ['name' => $ingredient?->name ?? 'Item']));
                return false;
            }
        }
        return true;
    }
}
