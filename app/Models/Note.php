<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'user_id', 'content'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (Note $note) {
            $admins = User::role('super admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NoteCreated($note));
            }
        });
    }
}
