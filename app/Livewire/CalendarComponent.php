<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CalendarComponent extends Component
{
    public $currentDate;
    public $view = 'month'; // month, week, day
    public $events = [];
    public $selectedEvent = null;
    public $showEventModal = false;
    public $showCreateModal = false;
    public $showDayModal = false;
    public $dayModalDate = null;
    public $dayModalEvents = [];
    
    // Form properties
    public $title = '';
    public $description = '';
    public $start_at = '';
    public $end_at = '';
    public $user_id = null;
    public $all_day = false;
    public $color = null;
    public $palette = [];

    protected $rules = [
        'title' => 'required|string|max:191',
        'description' => 'nullable|string',
        'start_at' => 'required|date',
        'end_at' => 'nullable|date|after_or_equal:start_at',
        'user_id' => 'nullable|exists:users,id',
        'all_day' => 'boolean',
        'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
    ];
    
    public function mount()
    {
        $this->currentDate = now();
        $this->palette = ['#4f6ba3', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b', '#06b6d4', '#ec4899'];
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $start = $this->getStartDate();
        $end = $this->getEndDate();
        
        $this->events = Event::query()
            ->visibleTo(auth()->user())
            ->whereBetween('start_at', [$start, $end])
            ->with(['user', 'creator'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_at,
                    'end' => $event->end_at ?? $event->start_at,
                    'color' => $event->color ?? $this->getEventColor($event->user_id), // Utilise la couleur de l'événement, sinon fallback
                    'allDay' => (bool) $event->all_day,
                    'description' => $event->description,
                    'calendar' => $event->user?->name,
                    'creator' => $event->creator?->name,
                    'user_id' => $event->user_id,
                ];
            })
            ->toArray();
    }

    public function changeView($view)
    {
        $this->view = $view;
        $this->loadEvents();
    }

    public function previousPeriod()
    {
        $this->currentDate = match($this->view) {
            'month' => $this->currentDate->copy()->subMonth(),
            'week' => $this->currentDate->copy()->subWeek(),
            'day' => $this->currentDate->copy()->subDay(),
        };
        
        $this->loadEvents();
    }

    public function nextPeriod()
    {
        $this->currentDate = match($this->view) {
            'month' => $this->currentDate->copy()->addMonth(),
            'week' => $this->currentDate->copy()->addWeek(),
            'day' => $this->currentDate->copy()->addDay(),
        };
        
        $this->loadEvents();
    }

    public function today()
    {
        $this->currentDate = now();
        $this->loadEvents();
    }

    public function selectEvent($eventId)
    {
        $event = Event::with(['user', 'creator'])->find($eventId);
        
        if ($event) {
            if ($this->showDayModal) {
                $this->closeDayModal();
            }

            $this->selectedEvent = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start_at,
                'end' => $event->end_at ?? $event->start_at,
                'allDay' => (bool) $event->all_day,
                'calendar' => $event->user?->name,
                'creator' => $event->creator?->name,
                'color' => $event->color ?? $this->getEventColor($event->user_id),
            ];
            $this->showEventModal = true;
        }
    }

    public function openCreateModal()
    {
        $this->reset(['title', 'description', 'start_at', 'end_at', 'user_id', 'all_day']);
        // default color (first palette)
        $this->color = $this->palette[0] ?? '#4f6ba3';
        $this->showCreateModal = true;
    }

    public function setColor(string $hex)
    {
        // basic sanitize
        if (preg_match('/^#([A-Fa-f0-9]{6})$/', $hex)) {
            $this->color = strtolower($hex);
        }
    }

    public function openCreateModalForDate(string $date)
    {
        $this->openCreateModal();

        $start = Carbon::parse($date)->setHour(9)->setMinute(0)->setSecond(0);
        $end = $start->copy()->addHour();

        $this->start_at = $start->format('Y-m-d\TH:i');
        $this->end_at = $end->format('Y-m-d\TH:i');

        $this->showDayModal = false;
        $this->dayModalDate = null;
        $this->dayModalEvents = [];
    }

    public function openDayModal(string $date)
    {
        $day = Carbon::parse($date);

        $this->dayModalDate = $day;
        $dayStart = $day->copy()->startOfDay();

        $this->dayModalEvents = collect($this->events)
            ->filter(function ($event) use ($dayStart) {
                $start = Carbon::parse($event['start'])->startOfDay();
                $end = Carbon::parse($event['end'] ?? $event['start'])->startOfDay();

                return $dayStart->between($start, $end, true);
            })
            ->sortBy(fn ($event) => Carbon::parse($event['start'])->format('H:i:s'))
            ->values()
            ->all();

        $this->showDayModal = true;
    }

    public function closeEventModal()
    {
        $this->showEventModal = false;
        $this->selectedEvent = null;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['title', 'description', 'start_at', 'end_at', 'user_id', 'all_day']);
    }

    public function closeDayModal()
    {
        $this->showDayModal = false;
        $this->dayModalDate = null;
        $this->dayModalEvents = [];
    }

    public function createEvent()
    { 
        try {
            $this->validate();

            Event::create([
                'title' => $this->title,
                'description' => $this->description,
                'start_at' => Carbon::parse($this->start_at),
                'end_at' => $this->end_at ? Carbon::parse($this->end_at) : Carbon::parse($this->start_at),
                'user_id' => $this->user_id,
                'color' => $this->color ? strtolower($this->color) : null,
                'created_by' => auth()->id(),
                'all_day' => $this->all_day,
            ]);

            $this->closeCreateModal();
            $this->loadEvents();

            Notification::make()
                ->title(__('calendar.event_created_success'))
                ->success()
                ->send();
        } catch (\Throwable $e) {
            // Log server error and add a Livewire validation error so the response remains JSON
            Log::error('CalendarComponent::createEvent error: ' . $e->getMessage(), ['exception' => $e]);

            // Add a top-level error for the UI
            $this->addError('server', __('An unexpected error occurred: :message', ['message' => $e->getMessage()]));

            // Optionally notify via Filament
            try {
                Notification::make()
                    ->title(__('calendar.event_create_failed') ?? 'Event creation failed')
                    ->danger()
                    ->send();
            } catch (\Throwable $_) {
                // ignore secondary errors
            }
        }
    }

    protected function getStartDate()
    {
        return match($this->view) {
            'month' => $this->currentDate->copy()->startOfMonth()->startOfWeek(),
            'week' => $this->currentDate->copy()->startOfWeek(),
            'day' => $this->currentDate->copy()->startOfDay(),
        };
    }

    protected function getEndDate()
    {
        return match($this->view) {
            'month' => $this->currentDate->copy()->endOfMonth()->endOfWeek(),
            'week' => $this->currentDate->copy()->endOfWeek(),
            'day' => $this->currentDate->copy()->endOfDay(),
        };
    }

    protected function getEventColor(?int $userId): string
    {
       // Cette fonction sert maintenant de fallback
        return $userId ? $this->palette[$userId % count($this->palette)] : $this->palette[0];
    }

    public function render()
    {
        $clientUsers = User::whereHas('roles', fn($q) => $q->where('name', 'client'))
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return view('livewire.calendar-component', [
            'days' => $this->getDaysForView(),
            'hours' => range(6, 22),
            'clientUsers' => $clientUsers,
            'isSuperAdmin' => auth()->user() && method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin(),
        ]);
    }

    protected function getDaysForView(): array
    {
        $start = $this->getStartDate();
        $end = $this->getEndDate();
        
        $days = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $eventsForDay = collect($this->events)->filter(function ($event) use ($current) {
                $eventStart = Carbon::parse($event['start']);
                return $eventStart->isSameDay($current);
            })->sortBy(function ($event) {
                return Carbon::parse($event['start'])->format('H:i');
            })->values();
            
            $days[] = [
                'date' => $current->copy(),
                'isCurrentMonth' => $current->month === $this->currentDate->month,
                'isToday' => $current->isToday(),
                'events' => $eventsForDay,
            ];
            
            $current->addDay();
        }
        
        return $days;
    }
}
