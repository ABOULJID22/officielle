<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\Event;
use App\Models\User;

class SendEventReminders extends Command
{
    protected $signature = 'events:remind {--days=1 : Number of days before event start to notify}';
    protected $description = 'Send database notifications to users for upcoming events.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        if ($days < 0) { $days = 1; }

        $tz = config('app.timezone', 'UTC');
        $start = Carbon::now($tz)->addDays($days)->startOfDay();
        $end = Carbon::now($tz)->addDays($days)->endOfDay();

        $events = Event::query()
            ->whereBetween('start_at', [$start, $end])
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events to remind.');
            return self::SUCCESS;
        }

        foreach ($events as $event) {
            // Determine recipients: event owner, assigned user, or all super admins as fallback
            $recipients = collect();
            if ($event->user_id) {
                $u = User::find($event->user_id);
                if ($u) { $recipients->push($u); }
            }
            if (method_exists($event, 'creator') && $event->creator) {
                $recipients->push($event->creator);
            }
            if ($recipients->isEmpty()) {
                $recipients = User::role('super_admin')->get();
            }

            if ($recipients->isEmpty()) { continue; }

            $title = $event->title ?: 'Événement';
            $when = Carbon::parse($event->start_at, $tz)->locale('fr_FR')->isoFormat('dddd D MMMM YYYY HH:mm');

            try {
                FilamentNotification::make()
                    ->title('Rappel: ' . $title)
                    ->body("Commence le: $when")
                    ->icon('heroicon-o-calendar')
                    ->sendToDatabase($recipients);
            } catch (\Throwable $e) {
                // continue on error
            }
        }

        $this->info('Event reminders processed.');
        return self::SUCCESS;
    }
}
