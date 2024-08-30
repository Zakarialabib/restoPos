<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.guest')]
#[Title('Cart')]
class CartComponent extends Component
{
    public $cart = [];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $this->cart[$productId] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] + 1 : 1,
        ];

        session()->put('cart', $this->cart);
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        session()->put('cart', $this->cart);
    }

    public function updateQuantity($productId, $quantity)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
        }
    }

    public function getTotalAmount()
    {
        return array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function render()
    {
        return view('livewire.cart-component', ['totalAmount' => $this->getTotalAmount()]);
    }
}