<?php

namespace App\Notifications;

use App\Models\PharmacistRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PharmacistRequestRejected extends Notification
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
            ->subject('Votre demande Pharmacien a été rejetée')
            ->line('Votre demande a été rejetée.')
            ->line('Motif: '.$this->request->admin_note)
            ->action('Nous contacter', route('contact.create'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pharmacist_request_rejected',
            'request_id' => $this->request->id,
            'reason' => $this->request->admin_note,
        ];
    }
}
