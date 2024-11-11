<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    protected Ingredient $ingredient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Low Stock Alert: ' . $this->ingredient->name)
            ->line('The stock for the ingredient "' . $this->ingredient->name . '" is running low.')
            ->line('Current stock level: ' . $this->ingredient->stock)
            ->action('View Ingredient', url('/ingredients/' . $this->ingredient->id))
            ->line('Please restock as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ingredient_id' => $this->ingredient->id,
            'ingredient_name' => $this->ingredient->name,
            'current_stock' => $this->ingredient->stock,
        ];
    }
}
