<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PageType;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasAdvancedFilter;
    use HasFactory, SoftDeletes;

    final public const ATTRIBUTES = [
        'id', 'title', 'slug',
        'type',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'is_homepage',
        'is_published',
        'template',
        'featured_image',
        'type',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_homepage' => 'boolean',
        'is_published' => 'boolean',
        'status' => Status::class,
        'type' => PageType::class,
        'settings' => 'array',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            // If the language_id is not set, set it to the current application locale
            if ( ! $section->language_id) {
                $language = Language::where('is_default', true)->first();

                if ($language) {
                    $section->language_id = $language->id;
                }
            }
        });
    }

    public function scopeActive($query): void
    {
        $query->where('status', true);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
