<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'role' => $this->role,

            'addresses' => AddressResource::collection($this->addresses),
            'cart' => new OrderResource($this->cart),
            'orders' => OrderResource::collection($this->orders),
            'created_at' => explode(' ', (new DateController())->toPersian($this->created_at))[0],

        ];
    }
}
