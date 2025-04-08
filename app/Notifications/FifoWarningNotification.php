<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Support\Carbon;

class FifoWarningNotification extends BaseNotification
{
    public function __construct(
        private readonly Ingredient $ingredient
    ) {
        $oldestStock = $this->ingredient->stockMovements()
            ->where('type', 'in')
            ->oldest()
            ->first();

        $daysInStock = $oldestStock ? $oldestStock->created_at->diffInDays(now()) : null;

        parent::__construct([
            'ingredient_id' => $this->ingredient->id,
            'ingredient_name' => $this->ingredient->name,
            'oldest_batch_date' => $oldestStock?->created_at?->toIso8601String(),
            'days_in_stock' => $daysInStock,
            'severity' => $this->calculateSeverity($daysInStock),
            'batch_number' => $oldestStock?->batch_number,
            'quantity' => $oldestStock?->quantity,
            'unit' => $this->ingredient->unit,
        ]);
    }

    public static function getType(): string
    {
        return 'fifo_warning';
    }

    protected function getMailSubject(): string
    {
        return 'FIFO Warning';
    }

    protected function getMailMessage(): string
    {
        $severity = $this->calculateSeverity($this->data['days_in_stock']);
        $daysInStock = $this->data['days_in_stock'];
        $oldestBatchDate = Carbon::parse($this->data['oldest_batch_date'])->format('Y-m-d');

        return match($severity) {
            'high' => "URGENT: Ingredient {$this->ingredient->name} has items that need to be used immediately based on FIFO principles. Oldest batch date: {$oldestBatchDate}, Days in stock: {$daysInStock}",
            'medium' => "Warning: Ingredient {$this->ingredient->name} has items that need to be used soon based on FIFO principles. Oldest batch date: {$oldestBatchDate}, Days in stock: {$daysInStock}",
            default => "Ingredient {$this->ingredient->name} has items that need to be used based on FIFO principles. Oldest batch date: {$oldestBatchDate}, Days in stock: {$daysInStock}",
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
     * Calculate the severity level based on the days in stock.
     */
    private function calculateSeverity(?int $daysInStock): string
    {
        if (null === $daysInStock) {
            return 'low';
        }

        return match(true) {
            $daysInStock >= 90 => 'high',
            $daysInStock >= 60 => 'medium',
            default => 'low',
        };
    }
}
