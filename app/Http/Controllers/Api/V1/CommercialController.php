<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Commercial;
use App\Http\Resources\V1\CommercialResource;
use Illuminate\Http\Request;

class CommercialController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Commercial::paginate($perPage);
        return CommercialResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Commercial::findOrFail($id);
        return new CommercialResource($item);
    }
}
