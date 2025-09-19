<?php

namespace App\Http\Controllers;
use App\Mail\ContactMessageMail;
use App\Mail\ContactThankYouMail;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;


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
            'name'       => ['required','string','max:100','regex:/^[\p{L}\s\'\-]+$/u'],
            'email'      => ['required','email'],
            'phone'      => ['required','string','max:20','regex:/^[\d\s+\-().]+$/'],
            'user_type'  => ['required','string','in:Acheteur,Futur pharmacien,Pharmacien titulaire,Autres'],
            'user_other' => ['nullable','string','max:100','regex:/^[\p{L}\s\'\-]+$/u'],
            'message'    => ['required','string','max:1500'],
        ], [
            'name.regex' => 'Le nom contient des caractères non autorisés.',
            'user_other.regex' => 'Le champ "Précisez" contient des caractères non autorisés.',
            'phone.regex' => 'Le téléphone ne doit contenir que chiffres, espaces, +, -, ( ).',
            'user_type.in' => 'Veuillez sélectionner une option valide.',
            'message.max' => 'Le message ne doit pas dépasser 1500 caractères.',
        ]);

        // Sauvegarde dans la base
        $contact = Contact::create($validated);

        // Create initial threaded support message
        try {
            if (class_exists(\App\Models\SupportMessage::class)) {
                \App\Models\SupportMessage::create([
                    'contact_id' => $contact->id,
                    'user_id' => null,
                    'body' => $contact->message,
                    'sender_type' => 'client',
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Initial support message not created: '.$e->getMessage());
        }

        // Notifier tous les super admins dans Filament (base de données)
        try {
            $admins = User::role(User::ROLE_SUPER_ADMIN)->get();
            if ($admins->isNotEmpty()) {
                $notification = FilamentNotification::make()
                    ->title('Nouveau message de contact')
                    ->body(sprintf('%s (%s)', $contact->name, $contact->email))
                    ->actions([
                        Action::make('view')
                            ->label('Voir les contacts')
                            ->url(route('filament.admin.resources.contacts.index'), true),
                    ]);

                // true => dispatch DatabaseNotificationsSent event (rafraîchit la cloche)
                $notification->sendToDatabase($admins, true);
            }
        } catch (\Throwable $e) {
            Log::warning('Notification admin non envoyée: '.$e->getMessage());
        }

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
            return redirect()->to(url()->previous() . '#contact')->with('success', "Votre message a bien été enregistré et l'email a été envoyé.");
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de contact: '.$e->getMessage(), [
                'contact_id' => $contact->id ?? null,
            ]);
            return redirect()->to(url()->previous() . '#contact')->with([
                'success' => 'Votre message a bien été enregistré !',
                'warning' => 'L\'email n\'a pas pu être envoyé pour le moment.'
            ]);
        }
    }
}
