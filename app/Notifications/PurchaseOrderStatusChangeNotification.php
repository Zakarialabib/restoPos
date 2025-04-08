<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PurchaseOrder;

class PurchaseOrderStatusChangeNotification extends BaseNotification
{
    public function __construct(
        private readonly PurchaseOrder $purchaseOrder
    ) {
        parent::__construct([
            'purchase_order_id' => $this->purchaseOrder->id,
            'reference' => $this->purchaseOrder->reference,
            'status' => $this->purchaseOrder->status,
            'severity' => $this->calculateSeverity(),
        ]);
    }

    public static function getType(): string
    {
        return 'purchase_order_status_change';
    }

    protected function getMailSubject(): string
    {
        return 'Purchase Order Status Updated';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity();

        return match($severity) {
            'high' => "URGENT: Purchase order #{$this->purchaseOrder->reference} status has been updated to {$this->purchaseOrder->status}",
            'medium' => "Purchase order #{$this->purchaseOrder->reference} status has been updated to {$this->purchaseOrder->status}",
            default => "Purchase order #{$this->purchaseOrder->reference} status has been updated to {$this->purchaseOrder->status}",
        };
    }

    protected function getMailActionText(): string
    {
        return 'View Purchase Order';
    }

    protected function getMailActionUrl(): string
    {
        return url("/admin/purchase-orders/{$this->purchaseOrder->id}");
    }

    /**
     * Calculate the severity level based on the status change.
     */
    private function calculateSeverity(): string
    {
        return match($this->purchaseOrder->status) {
            'cancelled', 'rejected' => 'high',
            'pending_approval', 'approved' => 'medium',
            default => 'low',
        };
    }
}
