<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;

class OrderStatusChangeNotification extends BaseNotification
{
    public function __construct(
        private readonly Order $order
    ) {
        parent::__construct([
            'order_id' => $this->order->id,
            'reference' => $this->order->reference,
            'status' => $this->order->status,
            'severity' => $this->calculateSeverity(),
            'total_amount' => $this->order->total_amount,
            'customer_name' => $this->order->customer_name,
        ]);
    }

    public static function getType(): string
    {
        return 'order_status_change';
    }

    protected function getMailSubject(): string
    {
        return 'Order Status Updated';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity();

        return match($severity) {
            'high' => "URGENT: Order #{$this->order->reference} status has been updated to {$this->order->status}",
            'medium' => "Order #{$this->order->reference} status has been updated to {$this->order->status}",
            default => "Order #{$this->order->reference} status has been updated to {$this->order->status}",
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
     * Calculate the severity level based on the status change.
     */
    private function calculateSeverity(): string
    {
        return match($this->order->status) {
            'cancelled', 'refunded' => 'high',
            'pending', 'processing' => 'medium',
            default => 'low',
        };
    }
}
