<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Ingredient $ingredient,
        private readonly string $type,
        private readonly array $details = []
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->subject($this->getSubject())
            ->line($this->getMessage());

        if ('low_stock' === $this->type) {
            $message->line("Current stock: {$this->ingredient->stock_quantity} {$this->ingredient->unit}");
            $message->line("Reorder point: {$this->ingredient->reorder_point} {$this->ingredient->unit}");
        } elseif ('expiring' === $this->type) {
            $message->line("Expiry date: {$this->details['expiry_date']}");
            $message->line("Quantity: {$this->details['quantity']} {$this->ingredient->unit}");
        }

        return $message;
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => $this->type,
            'ingredient_id' => $this->ingredient->id,
            'ingredient_name' => $this->ingredient->name,
            'details' => $this->details,
        ]);
    }

    private function getSubject(): string
    {
        return match ($this->type) {
            'low_stock' => "Low Stock Alert: {$this->ingredient->name}",
            'expiring' => "Expiring Stock Alert: {$this->ingredient->name}",
            default => "Inventory Alert: {$this->ingredient->name}",
        };
    }

    private function getMessage(): string
    {
        return match ($this->type) {
            'low_stock' => "The stock level for {$this->ingredient->name} has fallen below the reorder point.",
            'expiring' => "A batch of {$this->ingredient->name} is approaching its expiry date.",
            default => "There is an issue with the inventory for {$this->ingredient->name}.",
        };
    }
}
