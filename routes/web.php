<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SecurityReportController;
use App\Http\Controllers\PharmacistRequestController;
use App\Models\Post;
Route::get('/', [HomeController::class, 'index'])->name('home');




// Pages légales
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/politique-de-confidentialite', 'pages.privacy')->name('privacy');
// Page Pourquoi Offitrade
Route::view('/pourquoi-offitrade', 'pages.pourquoi')->name('pourquoi');

Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});


/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/pharmacist-request', [PharmacistRequestController::class, 'create'])->name('pharmacist.request.create');
    Route::post('/pharmacist-request', [PharmacistRequestController::class, 'store'])->name('pharmacist.request.store');
});

// Changer la langue (FR/EN) et revenir sur la page précédente — public
Route::get('/locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['fr', 'en'])) {
        $locale = config('app.fallback_locale');
    }
    session(['locale' => $locale]);
    return Redirect::back();
})->name('locale.set');



Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact/submit', [ContactController::class, 'submit'])
    ->middleware('throttle:contact-submissions')
    ->name('contact.submit');
Route::get('/contact', function () {
    return view('pages.contact');
});

// Minimal iframe routes to render only the Filament create forms (no surrounding layout)
Route::middleware('auth')->get('/admin/iframe/calendar/events/create', function () {
    return view('filament.iframe.create-event');
})->name('iframe.calendar.events.create');

Route::middleware('auth')->get('/admin/iframe/calendar/notes/create', function () {
    return view('filament.iframe.create-note');
})->name('iframe.calendar.notes.create');

// AJAX endpoints for calendar create actions used by the inline modals
Route::middleware(['auth', 'web'])->post('/calendar/events', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:191',
        'calendar_id' => 'nullable|integer|exists:users,id',
        'start_at' => 'required|string',
        'end_at' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    // Normalize and parse datetimes. Expect format from datetime-local: 'YYYY-MM-DDTHH:MM[:SS]'
    $startRaw = $validated['start_at'];
    $endRaw = $validated['end_at'] ?? null;

    try {
        // Ensure seconds are present when possible
        if (strpos($startRaw, 'T') !== false && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $startRaw)) {
            $startRaw .= ':00';
        }
        if ($endRaw && strpos($endRaw, 'T') !== false && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $endRaw)) {
            $endRaw .= ':00';
        }

        $startDt = \Carbon\Carbon::parse($startRaw);
        $endDt = $endRaw ? \Carbon\Carbon::parse($endRaw) : null;
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Invalid date format'], 422);
    }

    // Determine all_day: if both times are exactly at midnight and end is at 23:59:59 or absent, treat as all-day
    $allDay = false;
    if ($startDt->format('H:i:s') === '00:00:00' && (!$endDt || $endDt->format('H:i:s') === '23:59:59')) {
        $allDay = true;
    }

    $event = \App\Models\Event::create([
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'start_at' => $startDt,
        'end_at' => $endDt ?? $startDt,
        'user_id' => $validated['calendar_id'] ?? null,
        'created_by' => auth()->id(),
        'all_day' => $allDay,
    ]);

    // Filament database notification to the calendar owner (and creator) about the new event
    try {
        $recipients = collect();
        if (!empty($event->user_id)) {
            $owner = \App\Models\User::find($event->user_id);
            if ($owner) { $recipients->push($owner); }
        }
        // Also notify the creator if different
        $creator = \App\Models\User::find($event->created_by);
        if ($creator && ($recipients->isEmpty() || $creator->id !== $event->user_id)) {
            $recipients->push($creator);
        }

        if ($recipients->isNotEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Nouvel événement créé')
                ->body(($event->title ?: 'Événement').' — '.($startDt->locale('fr_FR')->isoFormat('dddd D MMMM YYYY') . (!$allDay ? ' ' . $startDt->format('HH:mm') : '')))
                ->icon('heroicon-o-calendar')
                ->sendToDatabase($recipients);
        }
    } catch (\Throwable $e) {
        // ignore notification errors
    }

    // Return the events for the current month to refresh calendar client-side
    $start = now()->startOfMonth()->format('Y-m-d');
    $end = now()->endOfMonth()->format('Y-m-d');
    $events = \App\Http\Controllers\CalendarController::indexData($start, $end);
    return response()->json($events);
});

Route::middleware(['auth', 'web'])->post('/calendar/notes', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'title' => 'required|string|max:191',
        'content' => 'nullable|string',
    ]);

    $note = \App\Models\Note::create([
        'title' => $data['title'],
        'content' => $data['content'] ?? null,
        'user_id' => auth()->id(),
    ]);

    return response()->json(['ok' => true, 'note' => $note]);
});
Route::get('/blog', [PostController::class, 'index'])->name('pages.blog.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('pages.blog.show'); // liaison par slug

require __DIR__.'/auth.php';


// CSP report-only endpoint (no CSRF)
Route::post('/.well-known/csp-report', [SecurityReportController::class, 'csp'])
    ->withoutMiddleware(['web'])
    ->name('security.csp.report');

// Client Support submission (in-panel, authenticated)
Route::post('/client/support', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:191',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:191',
        'message' => 'required|string',
    ]);

    $contact = \App\Models\Contact::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'user_type' => 'client',
        'user_other' => null,
        'message' => $validated['message'],
    ]);

    try {
        \Illuminate\Support\Facades\Mail::to(config('mail.from.address'))
            ->queue(new \App\Mail\ContactMessageMail($contact));
    } catch (\Throwable $e) {
        // ignore mail errors
    }

    return back()->with('status', 'Message envoyé');
})->name('client.support.submit')->middleware(['web', 'auth']);






