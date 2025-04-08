<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\ItemType;
use Illuminate\View\Component;

class TypeCard extends Component
{
    public function __construct(
        public ItemType $type,
        public int $total,
        public int $active,
        public ?string $subtitle = null
    ) {
    }

    public function render()
    {
        return view('components.type-card', [
            'bgGradient' => "from-{$this->type->color()}-50 to-{$this->type->color()}-100",
            'textColor' => "text-{$this->type->color()}-800",
            'iconBg' => "bg-{$this->type->color()}-200",
        ]);
    }
}
