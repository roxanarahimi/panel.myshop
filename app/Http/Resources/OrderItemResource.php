<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' =>  $this->order_id,
            'product_info' =>  new BaseProductResource($this->product->info),
            'product' =>  new ProductResource($this->product),
            'price' =>  $this->price,
            'off' =>  $this->off,
            'quantity' =>  $this->quantity,

        ];
    }
}
