<?php

namespace App\Mail;

use App\Models\PharmacistRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PharmacistRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PharmacistRequest $request) {}
 
    public function build()
    {
        return $this->subject('Nouvelle demande de profil Pharmacien')
            ->view('emails.pharmacist_request_received')
            ->with(['request' => $this->request]);
    }
}
