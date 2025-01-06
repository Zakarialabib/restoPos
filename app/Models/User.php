<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'settings',
        'has_seen_tutorial',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
        'has_seen_tutorial' => 'boolean',
        'role' => UserRole::class,
    ];


    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Methods
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function hasVerifiedEmail(): bool
    {
        return null !== $this->email_verified_at;
    }

    /**
     * Check if the user has seen the tutorial
     */
    public function hasSeenTutorial(): bool
    {
        return $this->has_seen_tutorial ?? false;
    }

    /**
     * Mark the tutorial as seen for the user
     */
    public function markTutorialAsSeen(): void
    {
        $this->update(['has_seen_tutorial' => true]);
    }
}
