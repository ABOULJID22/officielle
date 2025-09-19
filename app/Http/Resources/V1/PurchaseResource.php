<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => $this->whenLoaded('product', function () { return ['id'=>$this->product->id,'name'=>$this->product->name]; }),
            'commercial' => $this->whenLoaded('commercial', function () { return ['id'=>$this->commercial->id,'name'=>$this->commercial->name ?? $this->commercial->title]; }),
            'quantity' => $this->quantity ?? null,
            'price' => $this->price ?? null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
