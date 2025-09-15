<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    // --- Définition des rôles constants ---
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ASSISTANT   = 'assistant';
    const ROLE_CLIENT      = 'client';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'phone_2',
        'address',
        'city',
        'postal_code',
        'country',
        'job_title',
        'is_active',
        'avatar_url',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }



    
   // -------- Helpers pour vérifier les rôles -------- //
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isAssistant(): bool
    {
        return $this->hasRole(self::ROLE_ASSISTANT);
    }

    public function isClient(): bool
    {
        return $this->hasRole(self::ROLE_CLIENT);
    }


    public function posts()
{
    return $this->hasMany(Post::class, 'author_id');
}

}
