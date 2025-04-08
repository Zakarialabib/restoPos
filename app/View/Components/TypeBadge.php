<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\IngredientType;
use App\Enums\ItemType;
use Illuminate\View\Component;

class TypeBadge extends Component
{
    public $type;

    public function __construct(
        ItemType|IngredientType $type,
        public ?string $size = 'md'
    ) {
        $this->type = $type;
    }

    public function render()
    {
        $colorClasses = match($this->size) {
            'sm' => "text-xs px-2 py-0.5",
            'lg' => "text-base px-4 py-2",
            default => "text-sm px-3 py-1"
        };

        return view('components.type-badge', [
            'colorClasses' => $colorClasses,
            'bgColor' => 'bg-blue-100',
            'textColor' => 'text-blue-800',
            'icon' => 'shopping-cart',
            'label' => 'Item',
        ]);
    }
}
