<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.guest')]
#[Title('Checkout')]
class CheckoutComponent extends Component
{
    public $cart = [];
    public $customerName;
    public $customerPhone;
    public $showSuccessModal = false;
    public $order;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    // boot if cart is empty redirect to home
    // public function boot()
    // {
    //     if (empty($this->cart)) {
    //         return redirect()->route('index');
    //     }
    // }

    public function placeOrder()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|max:255',
        ]);

        $this->order = Order::create([
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'total_amount' => array_reduce($this->cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0),
            'status' => 'pending',
        ]);

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $this->order->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'details' => $item['ingredients'], // Pass the ingredients array
            ]);
        }

        session()->forget('cart');

        $this->successModal($this->order);
    }

    public function successModal($order)
    {
        $this->order = $order;
        $this->showSuccessModal = true;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }
}
