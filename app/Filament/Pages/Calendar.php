<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;

class Calendar extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Calendrier';
    protected static ?string $title = 'Calendrier';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.calendar';

    protected function getViewData(): array
    {
        // Provide calendars (users) for filter chips and an empty events array.
        // The frontend still fetches events via AJAX, but the view expects these variables.
        $calendars = \App\Models\User::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name])
            ->values();

        // Load events visible to the current authenticated user and shape them
        // for FullCalendar v3 (start/end as ISO strings, title, id, description, calendar name).
        $events = \App\Models\Event::query()
            ->visibleTo(auth()->user())
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'start' => optional($e->start_at)?->toIsoString(),
                    'end' => optional($e->end_at)?->toIsoString(),
                    'description' => $e->description,
                    'calendar' => $e->user?->name,
                    'creator' => $e->creator?->name,
                    'allDay' => (bool) ($e->all_day ?? false),
                    'calendar_id' => $e->user_id,
                    'color' => null, // frontend will derive color if null
                ];
            })->values();

        return [
            'events' => $events,
            'calendars' => $calendars,
        ];
    }

    
}
