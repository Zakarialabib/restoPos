<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;

class NewOrder extends BaseNotification
{
    public function __construct(
        private readonly Order $order
    ) {
        parent::__construct([
            'order_id' => $this->order->id,
            'reference' => $this->order->reference,
            'total_amount' => $this->order->total_amount,
            'customer_name' => $this->order->customer_name,
            'severity' => $this->calculateSeverity(),
            'items_count' => $this->order->items_count,
            'payment_method' => $this->order->payment_method,
        ]);
    }

    public static function getType(): string
    {
        return 'new_order';
    }

    protected function getMailSubject(): string
    {
        return 'New Order Received';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity();
        $formattedAmount = number_format($this->order->total_amount, 2);

        return match($severity) {
            'high' => "URGENT: New order #{$this->order->reference} has been received with total amount of {$formattedAmount}",
            'medium' => "New order #{$this->order->reference} has been received with total amount of {$formattedAmount}",
            default => "New order #{$this->order->reference} has been received with total amount of {$formattedAmount}",
        };
    }

    protected function getMailActionText(): string
    {
        return 'View Order';
    }

    protected function getMailActionUrl(): string
    {
        return url("/admin/orders/{$this->order->id}");
    }

    /**
     * Calculate the severity level based on the order amount and items count.
     */
    private function calculateSeverity(): string
    {
        return match(true) {
            $this->order->total_amount > 1000 || $this->order->items_count > 10 => 'high',
            $this->order->total_amount > 500 || $this->order->items_count > 5 => 'medium',
            default => 'low',
        };
    }
}
