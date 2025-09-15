<?php

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

   

    // Liaison par slug dans les routes implicites: /blog/{post:slug}
    public function getRouteKeyName(): string {
        return 'slug';
    }

   
}
