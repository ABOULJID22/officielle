<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 401);
        }

        $request->validate([
            'start' => 'required|string',
            'end' => 'required|string',
        ]);

        try {
            $start = Carbon::parse($request->get('start'))->startOfDay();
            $end = Carbon::parse($request->get('end'))->endOfDay();

            // 🔹 Tous les utilisateurs voient TOUS les événements
            $events = Event::query()
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_at', '<=', $end)
                      ->whereRaw('COALESCE(end_at, start_at) >= ?', [$start]);
                })
                ->with(['creator', 'user'])
                ->orderBy('start_at')
                ->get();

            $data = $events->map(function ($e) {
                $endForJson = $e->end_at ?: $e->start_at;
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'start' => optional($e->start_at)?->toIso8601String(),
                    'end' => optional($endForJson)?->toIso8601String(),
                    'allDay' => (bool) $e->all_day,
                    'color' => $this->getEventColor($e->user_id),
                    'calendar' => optional($e->user)->name,
                    'calendar_id' => $e->user_id,
                    'description' => $e->description,
                    'creator' => optional($e->creator)->name,
                ];
            });

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Calendar events fetch error: ' . $e->getMessage(), [
                'start' => $request->get('start'),
                'end' => $request->get('end'),
            ]);
            return response()->json([], 422);
        }
    }

    // Static helper to fetch events between two dates. Returns array suitable for JSON response.
    public static function indexData($start, $end)
    {
        try {
            $startDt = \Carbon\Carbon::parse($start)->startOfDay();
            $endDt = \Carbon\Carbon::parse($end)->endOfDay();

            $events = Event::query()
                ->where(function ($q) use ($startDt, $endDt) {
                    $q->where('start_at', '<=', $endDt)
                      ->whereRaw('COALESCE(end_at, start_at) >= ?', [$startDt]);
                })
                ->with(['creator', 'user'])
                ->orderBy('start_at')
                ->get();

            return $events->map(function ($e) {
                $endForJson = $e->end_at ?: $e->start_at;
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'start' => optional($e->start_at)?->toIso8601String(),
                    'end' => optional($endForJson)?->toIso8601String(),
                    'allDay' => (bool) $e->all_day,
                    'color' => (new self())->getEventColor($e->user_id),
                    'calendar' => optional($e->user)->name,
                    'calendar_id' => $e->user_id,
                    'description' => $e->description,
                    'creator' => optional($e->creator)->name,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            Log::error('Calendar indexData error: ' . $e->getMessage(), ['start' => $start, 'end' => $end]);
            return [];
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|string',
                'end_date' => 'required|string',
                'description' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
                'all_day' => 'nullable|boolean'
            ]);

            $user = Auth::user();
            
            // 🔒 Seuls les super admins peuvent créer
            if (!$user || !$user->isSuperAdmin()) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }

            $allDay = $request->boolean('all_day', false);

            if ($allDay) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            } else {
                $startDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date);
                $endDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_date);
            }

            if ($endDate->lt($startDate)) {
                return response()->json([
                    'errors' => ['end_date' => ['La date de fin doit être postérieure à la date de début']]
                ], 422);
            }

            $event = Event::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_at' => $startDate,
                'end_at' => $endDate,
                'all_day' => $allDay,
                'created_by' => $user->id,
                'user_id' => $validated['user_id'] ?? null,
            ]);

            return response()->json([
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_at->toISOString(),
                'end' => $event->end_at->toISOString(),
                'allDay' => $event->all_day,
                'color' => $this->getEventColor($event->user_id),
                'description' => $event->description,
                'creator' => $user->name,
                'calendar' => optional($event->user)->name,
                'calendar_id' => $event->user_id
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur création événement: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erreur lors de la création de l\'événement',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, Event $event)
    {
        $user = Auth::user();

        // 🔒 Seuls les super admins peuvent modifier
        if (!$user || !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|string',
                'end_date' => 'sometimes|string',
                'description' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
                'all_day' => 'sometimes|boolean'
            ]);

            $updateData = [];

            if ($request->has('title')) $updateData['title'] = $validated['title'];
            if ($request->has('description')) $updateData['description'] = $validated['description'];
            if ($request->has('user_id')) $updateData['user_id'] = $validated['user_id'] ?: null;
            if ($request->has('all_day')) $updateData['all_day'] = $request->boolean('all_day');

            if ($request->has('start_date') || $request->has('end_date')) {
                $allDay = $request->has('all_day') ? $request->boolean('all_day') : $event->all_day;
                
                $startDateStr = $request->start_date ?? $event->start_at->format($allDay ? 'Y-m-d' : 'Y-m-d\TH:i');
                $endDateStr = $request->end_date ?? $event->end_at->format($allDay ? 'Y-m-d' : 'Y-m-d\TH:i');

                if ($allDay) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $startDateStr)->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', $endDateStr)->endOfDay();
                } else {
                    $startDate = Carbon::createFromFormat('Y-m-d\TH:i', $startDateStr);
                    $endDate = Carbon::createFromFormat('Y-m-d\TH:i', $endDateStr);
                }

                if ($endDate->lt($startDate)) {
                    return response()->json([
                        'errors' => ['end_date' => ['La date de fin doit être postérieure à la date de début']]
                    ], 422);
                }

                $updateData['start_at'] = $startDate;
                $updateData['end_at'] = $endDate;
            }

            $event->update($updateData);

            return response()->json([
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_at->toISOString(),
                'end' => $event->end_at->toISOString(),
                'allDay' => $event->all_day,
                'color' => $this->getEventColor($event->user_id),
                'description' => $event->description,
                'creator' => optional($event->creator)->name,
                'calendar' => optional($event->user)->name,
                'message' => 'Événement mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour événement: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de l\'événement',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Event $event)
    {
        $user = Auth::user();
        
        // 🔒 Seuls les super admins peuvent supprimer
        if (!$user || !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $eventId = $event->id;
        $event->delete();

        return response()->json([
            'id' => $eventId,
            'message' => 'Événement supprimé avec succès'
        ]);
    }

    private function getEventColor($userId)
    {
        $palette = ['#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#84cc16'];
        return $userId === null ? $palette[0] : $palette[$userId % count($palette)];
    }
}
