<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasAdvancedFilter;
    use HasUuids;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'category',
        'description',
        'amount',
        'date',
        'payment_method',
        'reference_number',
        'notes',
        'created_by',
        'cash_register_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;
    public array $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'description',
        'amount',
        'date',
        'payment_method',
        'reference_number',
        'attachments',
        'notes',
        'created_by',
        'cash_register_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            related: ExpenseCategory::class,
            foreignKey: 'category'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($expense): void {
            $prefix = 'Exp-';
            $latestExpense = self::latest()->first();
            $number = $latestExpense ? (int) mb_substr((string) $latestExpense->reference_number, -3) + 1 : 1;
            $expense->reference_number = $prefix . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
        });
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): int|float => $value / 100,
            set: static fn ($value): int|float => $value * 100,
        );
    }
}
