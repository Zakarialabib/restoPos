<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $fillable = [
        'amount',
        'cost',
        'previous_amount',
        'effective_date',
        'is_current',
        'reason',
        'metadata',
        'priceable_type',
        'priceable_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cost' => 'decimal:2',
        'previous_amount' => 'decimal:2',
        'is_current' => 'boolean',
        'effective_date' => 'datetime',
        'metadata' => 'array'
    ];

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    // metadata convert to json  use Attribute
    protected function metadata(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
