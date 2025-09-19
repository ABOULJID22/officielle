<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Resources\V1\EventResource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Event::paginate($perPage);
        return EventResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Event::findOrFail($id);
        return new EventResource($item);
    }
}
