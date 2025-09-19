<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token = null, $resetUrl = null)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $data = [
            'user' => $this->user,
            'userName' => $this->user->name ?? $this->user->email,
            'token' => $this->token,
            'email' => $this->user->email ?? null,
            'resetUrl' => $this->resetUrl,
        ];

        return $this->subject('Réinitialisation du mot de passe — Offitrade')
                    ->view('emails.reset-password')
                    ->with($data);
    }
}
