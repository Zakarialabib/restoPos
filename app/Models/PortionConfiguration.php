<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortionConfiguration extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'type',
        'sizes',
        'addons',
        'sides',
        'upgrades',
        'is_active',
    ];

    protected $casts = [
        'sizes' => 'array',
        'addons' => 'array',
        'sides' => 'array',
        'upgrades' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getAvailableSizes(): array
    {
        return $this->sizes ?? match($this->type) {
            'burger' => ['single', 'double', 'triple'],
            'pizza' => ['small', 'medium', 'large', 'family'],
            'drink' => ['small', 'medium', 'large', 'x-large'],
            'fries' => ['small', 'medium', 'large', 'family'],
            'combo' => ['regular', 'large', 'mega'],
            default => ['regular', 'large'],
        };
    }

    public function getAvailableAddons(): array
    {
        return $this->addons ?? match($this->type) {
            'burger' => ['extra_cheese', 'extra_patty', 'bacon', 'avocado'],
            'pizza' => ['extra_cheese', 'extra_toppings', 'stuffed_crust'],
            'drink' => ['ice', 'lemon', 'straw'],
            'fries' => ['cheese', 'bacon', 'chili'],
            default => [],
        };
    }

    public function getAvailableSides(): array
    {
        return $this->sides ?? match($this->type) {
            'combo' => ['fries', 'drink', 'salad', 'dessert'],
            'burger' => ['fries', 'onion_rings', 'salad'],
            'pizza' => ['garlic_bread', 'salad', 'wings'],
            default => [],
        };
    }

    public function getAvailableUpgrades(): array
    {
        return $this->upgrades ?? match($this->type) {
            'combo' => ['premium_side', 'larger_drink', 'dessert_upgrade'],
            'burger' => ['premium_bun', 'premium_cheese', 'premium_sauce'],
            'pizza' => ['premium_toppings', 'stuffed_crust', 'extra_sauce'],
            default => [],
        };
    }

    public function validateSize(string $size): bool
    {
        return in_array($size, $this->getAvailableSizes());
    }

    public function validateAddon(string $addon): bool
    {
        return in_array($addon, $this->getAvailableAddons());
    }

    public function validateSide(string $side): bool
    {
        return in_array($side, $this->getAvailableSides());
    }

    public function validateUpgrade(string $upgrade): bool
    {
        return in_array($upgrade, $this->getAvailableUpgrades());
    }

    public function getPortionMultiplier(string $size): float
    {
        return match($size) {
            'small' => 0.8,
            'regular' => 1.0,
            'medium' => 1.2,
            'large' => 1.5,
            'x-large' => 2.0,
            'family' => 3.0,
            'single' => 1.0,
            'double' => 2.0,
            'triple' => 3.0,
            default => 1.0,
        };
    }
} 