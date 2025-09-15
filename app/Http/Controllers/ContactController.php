<?php

namespace App\Http\Controllers;
use App\Mail\ContactMessageMail;
use App\Mail\ContactThankYouMail;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact');
    }

    private function sendWithRetry(callable $send, int $attempts = 2, int $delaySeconds = 3, array $context = []): void
    {
        for ($i = 1; $i <= $attempts; $i++) {
            try {
                $send();
                return;
            } catch (\Throwable $e) {
                Log::warning('Echec d\'envoi email (tentative '.$i.'/'.$attempts.'): '.$e->getMessage(), $context);
                if ($i < $attempts && $this->isTransientRateLimit($e)) {
                    sleep($delaySeconds);
                    continue;
                }
                throw $e;
            }
        }
    }

    private function isTransientRateLimit(\Throwable $e): bool
    {
        $msg = $e->getMessage();
        if (stripos($msg, 'Too many emails per second') !== false) {
            return true;
        }
        // Common transient SMTP codes that might recover on retry
        if (preg_match('/\\b(421|450|451|452)\\b/', $msg)) {
            return true;
        }
        return false;
    }

    public function submit(Request $request)
    {
        // Validation des champs
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'user_type'  => 'required|string|max:255',
            'user_other' => 'nullable|string|max:255',
            'message'    => 'required|string',
        ]);

        // Sauvegarde dans la base
       /*  Contact::create($validated);*/

       $contact = Contact::create($validated);

        // Envoi d’email admin (CONTACT_TO) et email de remerciement à l'utilisateur (avec 1 retry après 3s)
        $to = env('CONTACT_TO', config('mail.from.address'));
        try {
            if ($to) {
                $this->sendWithRetry(function () use ($to, $contact) {
                    Mail::to($to)->send(new ContactMessageMail($contact));
                }, 2, 3, [
                    'contact_id' => $contact->id ?? null,
                    'to' => $to,
                    'kind' => 'admin'
                ]);
                Log::info('Email de contact (admin) envoyé avec succès.', [
                    'contact_id' => $contact->id ?? null,
                    'to' => $to,
                ]);
            } else {
                Log::warning('CONTACT_TO et mail.from.address non définis — email admin non envoyé.');
            }

            // Email de remerciement à l'utilisateur
            if (!empty($contact->email)) {
                $this->sendWithRetry(function () use ($contact) {
                    Mail::to($contact->email)->send(new ContactThankYouMail($contact));
                }, 2, 3, [
                    'contact_id' => $contact->id ?? null,
                    'user_email' => $contact->email,
                    'kind' => 'thank-you'
                ]);
                Log::info('Email de remerciement envoyé au contact.', [
                    'contact_id' => $contact->id ?? null,
                    'user_email' => $contact->email,
                ]);
            }
            return back()->with('success', "Votre message a bien été enregistré et l'email a été envoyé.");
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de contact: '.$e->getMessage(), [
                'contact_id' => $contact->id ?? null,
            ]);
            return back()->with([
                'success' => 'Votre message a bien été enregistré !',
                'warning' => 'L\'email n\'a pas pu être envoyé pour le moment.'
            ]);
        }
    }
}
