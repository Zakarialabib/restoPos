<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationType;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockLog extends Model
{
    use HasAdvancedFilter;
    use HasUuids;

    protected const ATTRIBUTES = [
        'id',
        'stockable_type',
        'stockable_id',
        'adjustment',
        'reason',
        'previous_quantity',
        'new_quantity',
        'user_id',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;
    protected $fillable = [
        'stockable_type',
        'stockable_id',
        'adjustment',
        'reason',
        'previous_quantity',
        'new_quantity',
        'user_id',
    ];

    protected $casts = [
        'adjustment' => 'float',
        'previous_quantity' => 'float',
        'new_quantity' => 'float',
        'reason' => NotificationType::class,
    ];

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
