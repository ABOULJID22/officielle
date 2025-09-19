<?php

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use  HasFactory,SoftDeletes;

    protected $fillable = ['name','slug','description'];

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

    // Translations
    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation(?string $locale = null): ?CategoryTranslation
    {
        $loc = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale');
        $loaded = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        return $loaded->firstWhere('locale', $loc)
            ?: ($fallback ? $loaded->firstWhere('locale', $fallback) : null);
    }

    public function getNameAttribute($value): ?string
    {
        return $this->translation()?->name ?? $value;
    }

    public function getSlugAttribute($value): ?string
    {
        return $this->translation()?->slug ?? $value;
    }

    public function getDescriptionAttribute($value): ?string
    {
        return $this->translation()?->description ?? $value;
    }
}

