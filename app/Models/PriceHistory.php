<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PriceHistory extends Model
{
    protected $fillable = [
        'amount',
        'notes',
        'effective_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'datetime',
    ];

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
