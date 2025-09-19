<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Contact $contact)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouveau message de contact',
            'body' => sprintf('%s (%s)', $this->contact->name, $this->contact->email),
            'actions' => [
                [
                    'label' => 'Voir les contacts',
                    'url' => route('filament.admin.resources.contacts.index'),
                ],
            ],
            'contact_id' => $this->contact->id,
        ];
    }
}
