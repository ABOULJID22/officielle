<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contact;
use App\Models\User;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SupportConversation extends Component
{
    public $contacts = [];
    public $selectedContactId = null;
    public $replySubject = 'Réponse à votre demande de support';
    public $editingMessage = false;
    public $editedMessage = '';
    public $threadMessages = [];
    public $adminReplyBody = '';
    public $clientReplyBody = '';

    public function mount()
    {
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $query = Contact::where('user_type', 'client');

        $u = auth()->user();
        if (! $u) {
            $query->whereRaw('1=0');
        } else {
            if (method_exists($u, 'isSuperAdmin') && $u->isSuperAdmin()) {
                // super admins see all
            } elseif (method_exists($u, 'isAssistant') && $u->isAssistant()) {
                // Assistants see messages from client pharmacies they manage via commercials mapping
                $userIds = DB::table('commercial_user as cu')
                    ->select('cu.user_id')
                    ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                    ->where('c.user_id', $u->id)
                    ->pluck('user_id')
                    ->toArray();
                if (! empty($userIds)) {
                    $emails = User::whereIn('id', $userIds)->pluck('email')->toArray();
                    if (! empty($emails)) {
                        $query->whereIn('email', $emails);
                    } else {
                        $query->whereRaw('1=0');
                    }
                } else {
                    $query->whereRaw('1=0');
                }
            } elseif (method_exists($u, 'isClient') && $u->isClient()) {
                // Clients only see messages that match their email
                $query->where('email', $u->email);
            } else {
                $query->whereRaw('1=0');
            }
        }

        $this->contacts = $query->orderByDesc('created_at')->limit(200)->get();
    }

    public function selectContact($id)
    {
        $this->selectedContactId = $id;
        $contact = Contact::find($id);
        $this->adminReplyBody = $contact?->reply_message ?? '';
        $this->clientReplyBody = '';
        $this->editedMessage = $contact?->message ?? '';
        $this->editingMessage = false;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (! $this->selectedContactId) {
            $this->threadMessages = [];
            return;
        }

        $contact = Contact::find($this->selectedContactId);
        if (! $contact) {
            $this->threadMessages = [];
            return;
        }

        $user = auth()->user();

        // Show full conversation to admins/assistants and to the client owner.
        if ($user && (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin() || method_exists($user, 'isAssistant') && $user->isAssistant())) {
            $this->threadMessages = $contact->messages()->orderBy('created_at')->get();
            return;
        }

        if ($user && method_exists($user, 'isClient') && $user->isClient()) {
            // Ensure the contact belongs to this client (email match)
            if ($contact->email === $user->email) {
                $this->threadMessages = $contact->messages()->orderBy('created_at')->get();
                return;
            }
        }

        // Fallback: empty
        $this->threadMessages = collect();
    }

    public function sendReply()
    {
        $this->validate([
            'selectedContactId' => 'required|integer',
            'adminReplyBody' => 'required|string',
        ]);
        $user = auth()->user();
        if (! $user || !(method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin() || method_exists($user, 'isAssistant') && $user->isAssistant())) {
            $this->addError('adminReplyBody', 'Non autorisé');
            return;
        }

        $contact = Contact::find($this->selectedContactId);
        if (! $contact) {
            $this->addError('selectedContactId', 'Message introuvable');
            return;
        }

        // create a threaded support message as admin
        \App\Models\SupportMessage::create([
            'contact_id' => $contact->id,
            'user_id' => $user->id,
            'body' => $this->adminReplyBody,
            'sender_type' => 'admin',
        ]);

    // queue email using admin reply body
    Mail::to($contact->email)->queue(new ContactReplyMail($contact, $this->replySubject, $this->adminReplyBody));

        $contact->update([
            'replied_at' => now(),
            'reply_message' => $this->adminReplyBody,
            'replied_by' => $user->id,
        ]);

    $this->loadContacts();
    $this->loadMessages();
    $this->dispatch('filament-notify', ['message' => 'Réponse envoyée']);
    }

    public function postMessageAsClient()
    {
        $this->validate([
            'selectedContactId' => 'required|integer',
            'clientReplyBody' => 'required|string',
        ]);

        $contact = Contact::find($this->selectedContactId);
        if (! $contact) {
            $this->addError('selectedContactId', 'Message introuvable');
            return;
        }

        $user = auth()->user();
        if (! $user || !(method_exists($user, 'isClient') && $user->isClient())) {
            $this->addError('clientReplyBody', 'Non autorisé');
            return;
        }

        // ensure the client is posting to their own contact (email match)
        if ($contact->email !== $user->email) {
            $this->addError('clientReplyBody', 'Non autorisé');
            return;
        }

        \App\Models\SupportMessage::create([
            'contact_id' => $contact->id,
            'user_id' => $user->id,
            'body' => $this->clientReplyBody,
            'sender_type' => 'client',
        ]);

        $contact->update(['message' => $this->clientReplyBody]);

    $this->loadContacts();
    $this->loadMessages();
    $this->dispatch('filament-notify', ['message' => 'Message ajouté']);
    }

    public function startEditMessage()
    {
        $this->editingMessage = true;
    }

    public function cancelEditMessage()
    {
        $this->editingMessage = false;
        $this->editedMessage = Contact::find($this->selectedContactId)?->message ?? '';
    }

    public function saveMessage()
    {
        $this->validate([
            'selectedContactId' => 'required|integer',
            'editedMessage' => 'required|string',
        ]);
        $contact = Contact::find($this->selectedContactId);
        if (! $contact) {
            $this->addError('selectedContactId', 'Message introuvable');
            return;
        }
        $contact->update(['message' => $this->editedMessage]);
    $this->editingMessage = false;
    $this->loadContacts();
    $this->dispatch('filament-notify', ['message' => 'Message mis à jour']);
    }

    public function render()
    {
        return view('livewire.support-conversation');
    }
}
