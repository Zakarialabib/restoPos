<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Ingredient;

class ExpiringIngredient extends BaseNotification
{
    public function __construct(
        private readonly Ingredient $ingredient
    ) {
        parent::__construct([
            'ingredient_id' => $ingredient->id,
            'ingredient_name' => $ingredient->name,
            'expiry_date' => $ingredient->expiry_date->toIso8601String(),
            'days_until_expiry' => now()->diffInDays($ingredient->expiry_date),
            'severity' => $this->calculateSeverity(),
        ]);
    }

    public static function getType(): string
    {
        return 'expiring_ingredient';
    }

    protected function getMailSubject(): string
    {
        return 'Expiring Ingredient Alert';
    }

    protected function getMailMessage(): string
    {
        $daysUntilExpiry = now()->diffInDays($this->ingredient->expiry_date);
        $severity = $this->calculateSeverity();

        return match($severity) {
            'high' => "URGENT: Ingredient {$this->ingredient->name} will expire in {$daysUntilExpiry} days on {$this->ingredient->expiry_date->format('Y-m-d')}",
            'medium' => "Warning: Ingredient {$this->ingredient->name} will expire in {$daysUntilExpiry} days on {$this->ingredient->expiry_date->format('Y-m-d')}",
            default => "Ingredient {$this->ingredient->name} will expire in {$daysUntilExpiry} days on {$this->ingredient->expiry_date->format('Y-m-d')}",
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
     * Calculate the severity level of the expiring ingredient.
     */
    private function calculateSeverity(): string
    {
        $daysUntilExpiry = now()->diffInDays($this->ingredient->expiry_date);

        return match(true) {
            $daysUntilExpiry <= 3 => 'high',
            $daysUntilExpiry <= 7 => 'medium',
            default => 'low',
        };
    }
}
