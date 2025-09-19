<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'user_id',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'commercial_user')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
