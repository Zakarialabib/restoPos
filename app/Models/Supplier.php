<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'payment_terms',
        'website',
        'bank_name',
        'bank_account',
        'bank_swift',
        'notes',
        'status',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;
    public array $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'payment_terms',
        'website',
        'bank_name',
        'bank_account',
        'bank_swift',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
