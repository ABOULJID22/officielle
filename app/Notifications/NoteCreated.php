<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NoteCreated extends Notification
{
    use Queueable;

    public function __construct(public Note $note) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'note_created',
            'note_id' => $this->note->id,
            'event_id' => $this->note->event_id,
            'content' => $this->note->content,
            'by' => optional($this->note->user)->name,
        ];
    }
}
