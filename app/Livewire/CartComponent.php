<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Cart')]
class CartComponent extends Component
{
    public $cart = [];

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    public function addToCart($productId): void
    {
        $product = Product::find($productId);

        if ( ! $product) {
            return;
        }

        $this->cart[$productId] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] + 1 : 1,
        ];

        session()->put('cart', $this->cart);
    }

    public function removeFromCart($productId): void
    {
        unset($this->cart[$productId]);
        session()->put('cart', $this->cart);
    }

    public function updateQuantity($productId, $quantity): void
    {
        if (isset($this->cart[$productId]) && $quantity > 0) {
            $this->cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
        } elseif ($quantity <= 0) {
            $this->removeFromCart($productId);
        }
    }

    public function getTotalAmount()
    {
        return array_reduce($this->cart, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
    }

    public function render()
    {
        return view('livewire.cart-component', ['totalAmount' => $this->getTotalAmount()]);
    }
}
