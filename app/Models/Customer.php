<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'user_id',
        'country',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'phone', 'email',
        'address',  'password', 'status',
        'customer_group_id', 'user_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearchByName($query, $name)
    {
        return $query->when( ! empty($name), fn ($query) => $query->where('name', 'like', '%' . $name . '%'));
    }
}
