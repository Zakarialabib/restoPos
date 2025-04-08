<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;

class PurchaseOrderNeededNotification extends BaseNotification
{
    public function __construct(
        private readonly Ingredient $ingredient
    ) {
        parent::__construct([
            'ingredient_id' => $this->ingredient->id,
            'ingredient_name' => $this->ingredient->name,
            'current_quantity' => $this->ingredient->quantity,
            'minimum_quantity' => $this->ingredient->minimum_quantity,
            'supplier_id' => $this->ingredient->supplier_id,
            'unit' => $this->ingredient->unit,
            'severity' => $this->calculateSeverity(),
            'stock_difference' => $this->ingredient->quantity - $this->ingredient->minimum_quantity,
        ]);
    }

    public static function getType(): string
    {
        return 'purchase_order_needed';
    }

    protected function getMailSubject(): string
    {
        return 'Purchase Order Needed';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity();
        $stockDifference = $this->ingredient->quantity - $this->ingredient->minimum_quantity;

        return match($severity) {
            'high' => "URGENT: Purchase order needed for {$this->ingredient->name}. Current stock: {$this->ingredient->quantity} {$this->ingredient->unit}, Minimum required: {$this->ingredient->minimum_quantity} {$this->ingredient->unit}",
            'medium' => "Purchase order needed for {$this->ingredient->name}. Current stock: {$this->ingredient->quantity} {$this->ingredient->unit}, Minimum required: {$this->ingredient->minimum_quantity} {$this->ingredient->unit}",
            default => "Purchase order needed for {$this->ingredient->name}. Current stock: {$this->ingredient->quantity} {$this->ingredient->unit}, Minimum required: {$this->ingredient->minimum_quantity} {$this->ingredient->unit}",
        };
    }

    protected function getMailActionText(): string
    {
        return 'Create Purchase Order';
    }

    protected function getMailActionUrl(): string
    {
        return url("/admin/purchase-orders/create?ingredient_id={$this->ingredient->id}");
    }

    /**
     * Calculate the severity level based on the stock difference.
     */
    private function calculateSeverity(): string
    {
        $stockDifference = $this->ingredient->quantity - $this->ingredient->minimum_quantity;

        return match(true) {
            $stockDifference <= 0 => 'high',
            $stockDifference <= 5 => 'medium',
            default => 'low',
        };
    }
}
