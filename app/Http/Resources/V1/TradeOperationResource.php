<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TradeOperationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', function () { return ['id'=>$this->user->id,'name'=>$this->user->name]; }),
            'description' => $this->description ?? null,
            'photos' => $this->whenLoaded('photos', function () { return $this->photos->map(fn($p)=>['id'=>$p->id,'url'=>$p->path ?? $p->url]); }),
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
