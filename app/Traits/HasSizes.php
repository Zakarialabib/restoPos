<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Computed;

trait HasSizes
{
    public $selectedSize;

    #[Computed]
    public function sizes(): array
    {
        return [
            'small' => [
                'name' => __('Small'),
                'capacity' => '250ml',
                'price_multiplier' => 1.0,
                'base_price' => 15.00,
            ],
            'medium' => [
                'name' => __('Medium'),
                'capacity' => '400ml',
                'price_multiplier' => 1.5,
                'base_price' => 20.00,
            ],
            'large' => [
                'name' => __('Large'),
                'capacity' => '600ml',
                'price_multiplier' => 2.0,
                'base_price' => 25.00,
            ],
        ];
    }

    public function getSizeCapacity(string $size): string
    {
        return match ($size) {
            'small' => '250ml',
            'medium' => '400ml',
            'large' => '600ml',
            default => '250ml',
        };
    }

    protected function getSizeMultiplier(): float
    {
        return $this->sizes()[$this->selectedSize]['price_multiplier'] ?? 1.0;
    }

    protected function getSizeBasePrice(): float
    {
        return $this->sizes()[$this->selectedSize]['base_price'] ?? 15.00;
    }
}
