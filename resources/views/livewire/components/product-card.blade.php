<?php

use function Livewire\Volt\{state, computed};




$addToCart = function () {
    $this->dispatch('addToCart', $this->product->id, $this->selectedSize, $this->quantity, $this->selectedOptions);
};
?>
