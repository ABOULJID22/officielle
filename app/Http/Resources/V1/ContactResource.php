<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? null,
            'message' => $this->message ?? null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
