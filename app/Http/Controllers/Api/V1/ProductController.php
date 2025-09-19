<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\V1\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of products.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $products = Product::query()->with(['category'])->paginate($perPage);

        return ProductResource::collection($products)->response();
    }

    /**
     * Display a single product.
     */
    public function show($id)
    {
        $product = Product::with(['category'])->findOrFail($id);
        return new ProductResource($product);
    }
}
