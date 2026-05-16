<?php

namespace App\Http\Controllers;

use App\Models\PharmacistRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

        // Determine whether to show the public form:
        // - Guests should see the form.
        // - Authenticated users should see the form when their account is NOT active (is_active == false).
        // This allows users whose accounts were deactivated by an admin to submit a new request.
        $showForm = false;
        if (! $user) {
            $showForm = true;
        } else {
            $showForm = empty($user->is_active) || $user->is_active === false;
        }

        return view('pharmacist_requests.create', [
            'pendingRequest' => $pendingRequest,
            'approvedRequest' => $approvedRequest,
            'user' => $user,
            'showForm' => $showForm,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'applicant_name' => ['required','string','max:191'],
            'applicant_email' => ['required','email','max:191'],
            'phone' => ['nullable','string','max:191'],
            'pharmacist_name' => ['required','string','max:191'],
            'pharmacy_address' => ['nullable','string'],
            'registration_number' => ['nullable','string','max:191'],
            'message' => 'nullable|string',
        ]);

        // Case-insensitive uniqueness check for pharmacy_name, excluding current user's own requests
        $validator->after(function ($v) use ($request, $user) {
            // the form now uses `pharmacist_name` as the pharmacy name field
            $name = trim((string) $request->input('pharmacist_name', ''));
            if ($name === '') {
                return;
            }
            // check existing requests (compare to stored pharmacy_name)
            $q = PharmacistRequest::whereRaw('LOWER(pharmacy_name) = ?', [Str::lower($name)]);
            if ($user) {
                $q->where('user_id', '<>', $user->id);
            }
            if ($q->exists()) {
                $v->errors()->add('pharmacy_name', __('pharmacist_request.pharmacy_name_taken_message'));
            }
            // Also ensure no existing user already uses that pharmacy_name
            $uq = User::whereRaw('LOWER(pharmacy_name) = ?', [Str::lower($name)]);
            if ($user) {
                $uq->where('id', '<>', $user->id);
            }
            if ($uq->exists()) {
                // Add error to the pharmacy_name field so the form shows the alert area
                $v->errors()->add('pharmacy_name', __('pharmacist_request.pharmacy_name_taken_message'));
            }
        });

        if ($validator->fails()) {
            return redirect()->route('pharmacist.request.create')
                ->withErrors($validator)
                ->withInput();
        }

    $data = $validator->validated();

        // Save pharmacist info on the user profile for review (only for authenticated users)
        $authUser = $request->user();
        if ($authUser) {
            $authUser->update([
                'pharmacist_name' => $data['applicant_name'],
                'registration_number' => $data['registration_number'] ?? null,
                'pharmacy_name' => $data['pharmacist_name'],
                'pharmacy_address' => $data['pharmacy_address'] ?? null,
                'pharmacy_phone' => $data['phone'] ?? null,
            ]);
        }

        // Prevent duplicate pending requests: for authenticated users, check by user_id;
        // for guests, check by applicant_email to avoid duplicate anonymous submissions.
        if ($authUser) {
            $existingPending = PharmacistRequest::where('user_id', $authUser->id)
                ->where('status', PharmacistRequest::STATUS_PENDING)
                ->first();
        } else {
            $existingPending = PharmacistRequest::where('applicant_email', $data['applicant_email'])
                ->where('status', PharmacistRequest::STATUS_PENDING)
                ->first();
        }

        if ($existingPending) {
            return redirect()->route('pharmacist.request.create')
                ->with('status', __('pharmacist_request.status.already_pending'));
        }

        try {
            $req = PharmacistRequest::create([
                'user_id' => $authUser?->id,
                'status' => PharmacistRequest::STATUS_PENDING,
                'message' => $data['message'] ?? null,
                'applicant_name' => $data['applicant_name'],
                'applicant_email' => $data['applicant_email'],
                'phone' => $data['phone'] ?? null,
                // store the submitted pharmacist_name in the pharmacy_name column
                'pharmacy_name' => $data['pharmacist_name'],
                'pharmacy_address' => $data['pharmacy_address'] ?? null,
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // DB-level unique constraint (MySQL 1062) — show friendly message instead of 500
            return redirect()->route('pharmacist.request.create')
                ->withErrors(['pharmacy_name' => __('pharmacist_request.pharmacy_name_taken_message')])
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback: if MySQL duplicate entry (1062) then treat as duplicate
            $code = $e->errorInfo[1] ?? null;
            if ($code === 1062) {
                return redirect()->route('pharmacist.request.create')
                    ->withErrors(['pharmacy_name' => __('pharmacist_request.pharmacy_name_taken_message')])
                    ->withInput();
            }
            throw $e;
        }

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

        return redirect()->route('pharmacist.request.create')->with('status', __('pharmacist_request.status.sent'));
    }
}
