<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'title' => $this->title,
            'city_id' => $this->city_id,
            'city' => $this->city,
            'province' => $this->city->province,
            'postal_code' => $this->postal_code,
            'address' => $this->address,

        ];
    }
}
