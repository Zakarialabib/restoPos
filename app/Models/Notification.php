<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasAdvancedFilter;
    use HasUuids;
    use SoftDeletes;

    public const TYPE_ORDER = 'order';
    public const TYPE_INVENTORY = 'inventory';
    public const TYPE_PURCHASE = 'purchase';
    public const TYPE_INGREDIENT = 'ingredient';
    public const TYPE_SYSTEM = 'system';

    protected const ATTRIBUTES = [
        'id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;
    public array $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): bool
    {
        if ( ! $this->read_at) {
            $this->update(['read_at' => now()]);
            return true;
        }
        return false;
    }

    public function markAsUnread(): bool
    {
        if ($this->read_at) {
            $this->update(['read_at' => null]);
            return true;
        }
        return false;
    }

    public function isRead(): bool
    {
        return null !== $this->read_at;
    }
}
