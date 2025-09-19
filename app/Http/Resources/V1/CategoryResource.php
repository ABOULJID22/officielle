<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? $this->title ?? null,
            'slug' => $this->slug ?? null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
