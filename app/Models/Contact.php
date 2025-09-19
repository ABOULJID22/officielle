<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'user_type', 'user_other', 'message',
        'replied_at', 'replied_by', 'reply_message',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
