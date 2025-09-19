<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'start_at', 'end_at', 'created_by', 'user_id', 'all_day'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'all_day' => 'bool',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleTo($query, User $user = null)
    {
        // If no user provided (shouldn't happen for authenticated endpoints), return empty.
        if (! $user) {
            return $query->whereRaw('1=0');
        }

        // Super admins can see all events.
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $query;
        }

        // Other users only see events assigned to them (user_id matches their id).
        return $query->where('user_id', $user->id);
    }
}
