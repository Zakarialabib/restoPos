<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\Severity;

class PurchaseOrderGeneratedNotification extends BaseNotification
{
    public function __construct(
        private readonly array $purchaseOrders
    ) {
        parent::__construct([
            'purchase_orders' => $this->purchaseOrders,
            'order_count' => count($this->purchaseOrders),
            'severity' => $this->calculateSeverity()->value,
        ]);
    }

    public static function getType(): string
    {
        return 'purchase_order_generated';
    }

    protected function getMailSubject(): string
    {
        return 'Purchase Orders Generated';
    }

    protected function getMailMessage(): string
    {
        $orderCount = count($this->purchaseOrders);
        $severity = $this->calculateSeverity();

        $message = match($severity) {
            Severity::HIGH => "URGENT: {$orderCount} new purchase orders have been generated based on critical stock levels.",
            Severity::MEDIUM => "{$orderCount} new purchase orders have been generated based on low stock levels.",
            Severity::LOW => "{$orderCount} new purchase orders have been generated.",
        };

        foreach ($this->purchaseOrders as $order) {
            $message .= "\n- Order #{$order['id']} for {$order['ingredient_name']}";
        }

        return $message;
    }

    protected function getMailActionText(): string
    {
        return 'View Purchase Orders';
    }

    protected function getMailActionUrl(): string
    {
        return url('/admin/purchase-orders');
    }

    /**
     * Calculate the severity level based on the number of orders and their urgency.
     */
    private function calculateSeverity(): Severity
    {
        $orderCount = count($this->purchaseOrders);
        $urgentOrders = array_filter($this->purchaseOrders, fn ($order) => $order['urgency'] ?? false);

        return match(true) {
            count($urgentOrders) > 0 => Severity::HIGH,
            $orderCount > 5 => Severity::MEDIUM,
            default => Severity::LOW,
        };
    }
}
