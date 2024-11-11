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
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
