<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'address',
        'logo_path',
        'favicon_path',
        'facebook_url',
        'linkedin_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'video_id',
        'bgvideo_url',
        'presentationvideo_url',
    ];
}
