<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\V1\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Category::query()->paginate($perPage);
        return CategoryResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Category::findOrFail($id);
        return new CategoryResource($item);
    }
}
