<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Http\Resources\V1\PurchaseResource;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Purchase::with(['commercial','product'])->paginate($perPage);
        return PurchaseResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Purchase::with(['commercial','product'])->findOrFail($id);
        return new PurchaseResource($item);
    }
}
