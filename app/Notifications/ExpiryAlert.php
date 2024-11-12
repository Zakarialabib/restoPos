<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiryAlert extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Ingredient $ingredient
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysUntilExpiry = now()->diffInDays($this->ingredient->expiry_date);
        
        return (new MailMessage())
            ->subject('Ingredient Expiry Alert')
            ->greeting('Hello!')
            ->line("The ingredient '{$this->ingredient->name}' will expire in {$daysUntilExpiry} days.")
            ->line("Current stock: {$this->ingredient->stock} {$this->ingredient->unit->value}")
            ->action('View Ingredient', url("/admin/ingredients/{$this->ingredient->id}"))
            ->line('Please take necessary action to prevent waste.');
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
            'expiry_date' => $this->ingredient->expiry_date->format('Y-m-d'),
            'current_stock' => $this->ingredient->stock,
            'unit' => $this->ingredient->unit->value
        ];
    }
}
