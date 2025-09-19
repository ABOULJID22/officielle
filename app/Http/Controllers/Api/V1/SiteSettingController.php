<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Http\Resources\V1\SiteSettingResource;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(Request $request)
    {
        $items = SiteSetting::latest('id')->limit(1)->get();
        return SiteSettingResource::collection($items)->response();
    }

    public function show($id)
    {
        $item = SiteSetting::findOrFail($id);
        return new SiteSettingResource($item);
    }
}
