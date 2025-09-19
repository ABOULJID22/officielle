<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\V1\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Post::with(['translations'])->paginate($perPage);
        return PostResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Post::with(['translations'])->findOrFail($id);
        return new PostResource($item);
    }
}
