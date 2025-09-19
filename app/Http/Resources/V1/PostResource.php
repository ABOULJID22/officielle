<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? $this->name ?? null,
            'excerpt' => $this->excerpt ?? null,
            'body' => $this->body ?? null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
