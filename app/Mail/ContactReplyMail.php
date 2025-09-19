<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;
    public string $subjectLine;
    public string $body;

    public function __construct(Contact $contact, string $subjectLine, string $body)
    {
        $this->contact = $contact;
        $this->subjectLine = $subjectLine;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.contact-reply', [
                'contact' => $this->contact,
                'body' => $this->body,
            ]);
    }
}
