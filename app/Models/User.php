<?php

declare(strict_types=1);

namespace App\Models;

// use App\Enums\UserRole; // Commented out existing role enum
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuids;
    use Notifiable;
    use HasRoles;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        // 'role' => UserRole::class, // Commented out existing role attribute
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

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
        // 'role' => UserRole::class, // Commented out existing role cast
    ];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
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
