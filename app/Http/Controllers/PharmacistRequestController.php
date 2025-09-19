<?php

namespace App\Http\Controllers;

use App\Models\PharmacistRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\PharmacistRequestReceived;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as FilamentAction;

class PharmacistRequestController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $pendingRequest = null;
        $approvedRequest = null;
        if ($user) {
            $pendingRequest = PharmacistRequest::query()
                ->where('user_id', $user->id)
                ->where('status', PharmacistRequest::STATUS_PENDING)
                ->latest()
                ->first();
            $approvedRequest = PharmacistRequest::query()
                ->where('user_id', $user->id)
                ->where('status', PharmacistRequest::STATUS_APPROVED)
                ->latest('approved_at')
                ->first();
        }

        return view('pharmacist_requests.create', [
            'pendingRequest' => $pendingRequest,
            'approvedRequest' => $approvedRequest,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'applicant_name' => ['required','string','max:191'],
            'applicant_email' => ['required','email','max:191'],
            'phone' => ['nullable','string','max:191'],
            'pharmacy_name' => ['required','string','max:191'],
            'pharmacy_address' => ['nullable','string'],
            'registration_number' => ['nullable','string','max:191'],
            'message' => 'nullable|string',
        ]);

        // Save pharmacist info on the user profile for review
        $user = $request->user();
        $user->update([
            'pharmacist_name' => $data['applicant_name'],
            'registration_number' => $data['registration_number'] ?? null,
            'pharmacy_name' => $data['pharmacy_name'],
            'pharmacy_address' => $data['pharmacy_address'] ?? null,
            'pharmacy_phone' => $data['phone'] ?? null,
        ]);

        // Prevent duplicate pending requests per user
        $existingPending = PharmacistRequest::where('user_id', $user->id)
            ->where('status', PharmacistRequest::STATUS_PENDING)
            ->first();

        if ($existingPending) {
            return redirect()->route('pharmacist.request.create')
                ->with('status', 'Votre demande est déjà en cours de validation.');
        }

        $req = PharmacistRequest::create([
            'user_id' => $user->id,
            'status' => PharmacistRequest::STATUS_PENDING,
            'message' => $data['message'] ?? null,
            'applicant_name' => $data['applicant_name'],
            'applicant_email' => $data['applicant_email'],
            'phone' => $data['phone'] ?? null,
            'pharmacy_name' => $data['pharmacy_name'],
            'pharmacy_address' => $data['pharmacy_address'] ?? null,
        ]);

        // Notify super admins
        try {
            $admins = User::role('super_admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new \App\Notifications\PharmacistRequestSubmitted($req));
                // Filament database notification (visible in admin topbar)
                try {
                    $adminUrl = url('/admin/pharmacist-requests');
                    FilamentNotification::make()
                        ->title('Nouvelle demande Pharmacien')
                        ->body(($user->name ?? $data['applicant_name']).' a soumis une demande'.($req->pharmacy_name ? ' pour '.$req->pharmacy_name : '').'.')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->success()
                        ->actions([
                            FilamentAction::make('voir')
                                ->label('Voir')
                                ->url($adminUrl)
                                ->button(),
                        ])
                        ->sendToDatabase($admins);
                } catch (\Throwable $e) {
                    // ignore filament db notification errors
                }
                // Also attempt direct emails to each admin as a fallback
                try {
                    foreach ($admins as $admin) {
                        \Illuminate\Support\Facades\Mail::to($admin->email)
                            ->send(new \App\Mail\PharmacistRequestReceived($req));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Error sending admin fallback mails for pharmacist request: '.$e->getMessage());
                }
            }
        } catch (\Throwable $e) {}

        // Send an email to contact address
        try {
            $to = env('CONTACT_TO', config('mail.from.address'));
            Mail::to($to)->send(new PharmacistRequestReceived($req));
        } catch (\Throwable $e) {
            // ignore mail errors
        }

        return redirect()->route('pharmacist.request.create')->with('status', 'Demande envoyée.');
    }
}
