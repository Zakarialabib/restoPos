<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;

class StockLevel extends BaseNotification
{
    public function __construct(
        private readonly Ingredient $ingredient
    ) {
        parent::__construct([
            'ingredient_id' => $ingredient->id,
            'ingredient_name' => $ingredient->name,
            'current_stock' => $ingredient->current_stock,
            'minimum_stock' => $ingredient->minimum_stock,
            'severity' => $this->calculateSeverity(),
            'stock_difference' => $ingredient->current_stock - $ingredient->minimum_stock,
        ]);
    }

    public static function getType(): string
    {
        return 'stock_level';
    }

    protected function getMailSubject(): string
    {
        return 'Stock Level Alert';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity();
        $stockDifference = $this->ingredient->current_stock - $this->ingredient->minimum_stock;

        return match($severity) {
            'high' => "CRITICAL: {$this->ingredient->name} is out of stock! Current stock: {$this->ingredient->current_stock}, Minimum required: {$this->ingredient->minimum_stock}",
            'medium' => "Warning: {$this->ingredient->name} is running low on stock. Current stock: {$this->ingredient->current_stock}, Minimum required: {$this->ingredient->minimum_stock}",
            default => "{$this->ingredient->name} stock level is low. Current stock: {$this->ingredient->current_stock}, Minimum required: {$this->ingredient->minimum_stock}",
        };
    }

    protected function getMailActionText(): string
    {
        return 'View Ingredient';
    }

    protected function getMailActionUrl(): string
    {
        return url("/admin/ingredients/{$this->ingredient->id}");
    }

    /**
     * Calculate the severity level of the stock level.
     */
    private function calculateSeverity(): string
    {
        $stockDifference = $this->ingredient->current_stock - $this->ingredient->minimum_stock;

        return match(true) {
            $stockDifference <= 0 => 'high',
            $stockDifference <= 5 => 'medium',
            default => 'low',
        };
    }
}
