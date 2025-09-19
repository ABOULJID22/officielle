<?php

namespace App\Notifications;

use App\Models\PharmacistRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PharmacistRequestApproved extends Notification
{
    use Queueable;

    public function __construct(public PharmacistRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande Pharmacien a été acceptée')
            ->view('emails.pharmacist_request_approved', [
                'request' => $this->request,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pharmacist_request_approved',
            'request_id' => $this->request->id,
        ];
    }
}
