<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\Severity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification data that will be stored in the database.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Create a new notification instance.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = array_merge([
            'type' => static::getType(),
            'timestamp' => now()->toIso8601String(),
        ], $data);
    }

    /**
     * Get the notification type.
     */
    abstract public static function getType(): string;

    /**
     * Get the mail subject for the notification.
     */
    abstract protected function getMailSubject(): string;

    /**
     * Get the mail message for the notification.
     */
    abstract protected function getMailMessage(): string;

    /**
     * Get the mail action text for the notification.
     */
    abstract protected function getMailActionText(): string;

    /**
     * Get the mail action URL for the notification.
     */
    abstract protected function getMailActionUrl(): string;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'data' => $this->data,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject($this->getMailSubject())
            ->line($this->getMailMessage())
            ->action($this->getMailActionText(), $this->getMailActionUrl())
            ->line('Thank you for using our application!');
    }

    /**
     * Get the notification data.
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the notification timestamp.
     */
    public function getTimestamp(): Carbon
    {
        return Carbon::parse($this->data['timestamp']);
    }

    /**
     * Get the notification severity.
     */
    public function getSeverity(): Severity
    {
        return Severity::from($this->data['severity']);
    }
}
