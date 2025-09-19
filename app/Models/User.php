<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Models\Contracts\HasAvatar as FilamentHasAvatar;

class User extends Authenticatable implements FilamentHasAvatar
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
        'website',
        'phone', 
        'phone_2',
        'address',
        'city',
        'postal_code',
        'country',
        'job_title',
        'pharmacist_name',
        'registration_number',
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

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function tradeOperations()
    {
        return $this->hasMany(TradeOperation::class);
    }

    public function commercials()
    {
        return $this->belongsToMany(Commercial::class, 'commercial_user')->withTimestamps();
    }

    // Expose a unified avatar URL with fallback
    public function getAvatarAttribute(): string
    {
        $state = $this->avatar_url ?? null;

        if (!$state) {
            return asset('images/avater.png');
        }

        if (Str::startsWith($state, ['http://', 'https://'])) {
            return $state;
        }
 
        if (Str::startsWith($state, '/storage/')) {
            $relative = ltrim(Str::after($state, '/storage/'), '/');
            return Storage::disk('public')->exists($relative)
                ? $state
                : asset('images/avater.png');
        }

        if (Str::contains($state, ['storage/app/public', 'storage\\app\\public'])) {
            $state = 'avatar/' . basename($state);
        }

        return Storage::disk('public')->exists($state)
            ? Storage::disk('public')->url($state)
            : asset('images/avater.png');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ?: null;
    }

    /**
     * Use our custom Mailable for password reset emails.
     */
    public function sendPasswordResetNotification($token)
    {
        try {
            $mail = new \App\Mail\ResetPasswordMail($this, $token);
            \Illuminate\Support\Facades\Mail::to($this->email)->send($mail);
        } catch (\Throwable $e) {
            // Fallback to the default notification if mail sending fails
            parent::sendPasswordResetNotification($token);
        }
    }

}
