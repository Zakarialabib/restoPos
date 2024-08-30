<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ComposableJuice;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

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

    public $fruits = [];
    public $bases = ['Water', 'Orange Juice', 'Milk'];
    public $addons = ['Dried Fruits', 'Nuts', 'Seeds'];

    public function mount()
    {
        $this->fruits = Product::where('is_composable', true)->get();
        $this->cart = session()->get('cart', []);
    }

    public function boot()
    {
        session()->forget('cart');
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function toggleFruit($fruit)
    {
        if (in_array($fruit, $this->selectedFruits)) {
            $this->selectedFruits = array_diff($this->selectedFruits, [$fruit]);
        } else {
            $this->selectedFruits[] = $fruit;
        }
    }

    public function toggleAddon($addon)
    {
        if (in_array($addon, $this->selectedAddons)) {
            $this->selectedAddons = array_diff($this->selectedAddons, [$addon]);
        } else {
            $this->selectedAddons[] = $addon;
        }
    }

    public function addToCart()
    {
        $this->cart[] = [
            'name' => 'Custom Juice',
            'price' => 5.00, // Example price
            'quantity' => 1,
            'ingredients' => [
                'fruits' => $this->selectedFruits,
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'addons' => $this->selectedAddons,
            ],
        ];
        session()->put('cart', $this->cart);

        // return redirect()->route('cart');
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        session()->put('cart', $this->cart);
    }

    public function render()
    {
        $composableJuices = ComposableJuice::all();
        return view('livewire.composable-juices-index', ['composableJuices' => $composableJuices]);
    }
}
