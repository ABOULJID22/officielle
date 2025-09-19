<?php

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title','slug','content','cover_image',
        'author_id','category_id','status','published_at',
        'reading_time',
    ];
 
    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    // Translations
    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation(?string $locale = null): ?PostTranslation
    {
        $loc = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale');
        $loaded = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        return $loaded->firstWhere('locale', $loc)
            ?: ($fallback ? $loaded->firstWhere('locale', $fallback) : null);
    }

    // Locale-aware accessors
    public function getTitleAttribute($value): ?string
    {
        return $this->translation()?->title ?? $value;
    }

    public function getContentAttribute($value): ?string
    {
        return $this->translation()?->content ?? $value;
    }

    public function getSlugAttribute($value): ?string
    {
        return $this->translation()?->slug ?? $value;
    }

    
    // Liaison par slug dans les routes implicites: /blog/{post:slug}
    public function getRouteKeyName(): string {
        return 'slug';
    }

    // Supporte la résolution par slug traduit
    public function resolveRouteBinding($value, $field = null)
    {
        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale');

        return static::query()
            ->where($field ?? 'slug', $value)
            ->orWhereHas('translations', function ($q) use ($value, $locale, $fallback) {
                $q->where(function ($qq) use ($value, $locale) {
                    $qq->where('locale', $locale)->where('slug', $value);
                });
                if ($fallback) {
                    $q->orWhere(function ($qq) use ($value, $fallback) {
                        $qq->where('locale', $fallback)->where('slug', $value);
                    });
                }
            })
            ->first();
    }

   
}
