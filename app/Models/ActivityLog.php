<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'causer_type',
        'causer_id',
        'subject_type',
        'subject_id',
        'action',
        'description',
        'properties',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function getChangesAttribute(): array
    {
        if ( ! $this->old_values || ! $this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            if ( ! isset($this->old_values[$key]) || $this->old_values[$key] !== $newValue) {
                $changes[$key] = [
                    'old' => $this->old_values[$key] ?? null,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }
}
