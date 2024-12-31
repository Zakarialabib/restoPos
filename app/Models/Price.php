<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $fillable = [
        'priceable_type',
        'priceable_id',
        'cost',
        'price',
        'effective_date',
        'notes',
        'is_current'
    ];

    protected $casts = [
        'cost' => 'float',
        'price' => 'float',
        'effective_date' => 'date',
        'is_current' => 'boolean'
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
