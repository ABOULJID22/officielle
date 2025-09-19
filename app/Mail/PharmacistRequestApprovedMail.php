<?php

namespace App\Mail;

use App\Models\PharmacistRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PharmacistRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PharmacistRequest $request) {}

    public function build() 
    {
        return $this->subject('Votre profil Pharmacien a été approuvé')
            ->view('emails.pharmacist_request_approved')
            ->with(['request' => $this->request]);
    }
}
