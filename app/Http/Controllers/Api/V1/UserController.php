<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = User::paginate($perPage);
        return UserResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = User::findOrFail($id);
        return new UserResource($item);
    }
}
