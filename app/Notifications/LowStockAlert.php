<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Support\Facades\URL;

class LowStockAlert extends BaseNotification
{
    public function __construct(
        protected Ingredient $ingredient
    ) {
        parent::__construct([
            'ingredient_id' => $ingredient->id,
            'ingredient_name' => $ingredient->name,
            'current_stock' => $ingredient->current_stock,
            'minimum_stock' => $ingredient->minimum_stock,
            'unit' => $ingredient->unit,
        ]);
    }

    public static function getType(): string
    {
        return 'low-stock';
    }

    protected function getMailSubject(): string
    {
        return "Low Stock Alert: {$this->ingredient->name}";
    }

    protected function getMailMessage(): string
    {
        return "The stock level for {$this->ingredient->name} is running low. Current stock: {$this->ingredient->current_stock} {$this->ingredient->unit}";
    }

    protected function getMailActionText(): string
    {
        return 'View Ingredient';
    }

    protected function getMailActionUrl(): string
    {
        return URL::route('admin.ingredients.show', $this->ingredient);
    }
}
