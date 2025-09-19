<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TradeOperation;
use App\Http\Resources\V1\TradeOperationResource;
use Illuminate\Http\Request;

class TradeOperationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = TradeOperation::with(['user','photos'])->paginate($perPage);
        return TradeOperationResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = TradeOperation::with(['user','photos'])->findOrFail($id);
        return new TradeOperationResource($item);
    }
}
