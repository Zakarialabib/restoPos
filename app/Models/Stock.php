<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $fillable = [
        'ingredient_id',
        'quantity',
        'unit',
        'minimum_quantity',
        'reorder_point',
        'location',
        'last_restocked_at',
        'last_checked_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'minimum_quantity' => 'decimal:2',
        'reorder_point' => 'decimal:2',
        'last_restocked_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_point;
    }

    public function needsRestock(): bool
    {
        return $this->quantity <= $this->minimum_quantity;
    }
}
