<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Resources\V1\ContactResource;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $items = Contact::paginate($perPage);
        return ContactResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = Contact::findOrFail($id);
        return new ContactResource($item);
    }
}
