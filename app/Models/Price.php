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
    
    public function getUnit(): ?string
    {
        return $this->metadata['unit'] ?? null;
    }

    public function getPricePerUnit(): ?float
    {
        return $this->price / $this->getSize();
    }

    public function getCostPerUnit(): ?float
    {
        return $this->cost / $this->getSize();
    }

    public function getProfitPerUnit(): ?float
    {
        return $this->getPricePerUnit() - $this->getCostPerUnit();
    }

    public function getProfitPercentage(): ?float
    {
        return $this->getProfitPerUnit() / $this->getCostPerUnit();
    }

    public function getProfitMargin(): ?float
    {
        return $this->getProfitPercentage() * 100;
    }

    public function getPriceForSizeAndUnit(): ?float
    {
        return $this->price / ($this->metadata['size'] ?? 1);
    }

}
