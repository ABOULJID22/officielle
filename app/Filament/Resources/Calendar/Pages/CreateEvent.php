<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $request = request();
        if ($request->has('start_at')) {
            $data['start_at'] = Carbon::parse($request->query('start_at'))->toDateTimeString();
        }
        if ($request->has('end_at')) {
            $data['end_at'] = Carbon::parse($request->query('end_at'))->toDateTimeString();
        }
        if ($request->has('all_day')) {
            $data['all_day'] = $request->boolean('all_day');
        }
        if (!($data['created_by'] ?? null) && auth()->check()) {
            $data['created_by'] = auth()->id();
        }
        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $request = request();
        if ($request->has('start_at') && !($data['start_at'] ?? null)) {
            $data['start_at'] = Carbon::parse($request->query('start_at'))->toDateTimeString();
        }
        if ($request->has('end_at') && !($data['end_at'] ?? null)) {
            $data['end_at'] = Carbon::parse($request->query('end_at'))->toDateTimeString();
        }
        if ($request->has('all_day')) {
            $data['all_day'] = $request->boolean('all_day');
        }

        if (!($data['created_by'] ?? null) && auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        // Ensure end_at exists: if left empty, default to start_at to avoid missing in calendar
        if (!($data['end_at'] ?? null) && ($data['start_at'] ?? null)) {
            $data['end_at'] = Carbon::parse($data['start_at'])->toDateTimeString();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $url = static::getResource()::getUrl('index');
        if (request()->boolean('in_iframe')) {
            return $url . '?created=1&in_iframe=1';
        }
        return $url;
    }
}
