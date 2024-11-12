<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $fillable = [
        'cost',
        'price',
        'date',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
        'date' => 'datetime',
        'metadata' => 'array',
    ];

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getSize(): ?string
    {
        return $this->metadata['size'] ?? null;
    }
}
