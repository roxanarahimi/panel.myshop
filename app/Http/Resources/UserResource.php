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
            'password' => $this->password,
            'role' => $this->role,

            'address' => [
                'postal_code' => $this->address?->postal_code,
                'id' => $this->address?->address,
                'title' => $this->address?->title
            ],

            'city' => [
                'id' => $this->address?->city?->id,
                'name' => $this->address?->city?->name
            ],
            'province' => [
                'id' => $this->address?->city?->province->id,
                'name' => $this->address?->city?->province->name
            ],
            'created_at' => explode(' ', (new DateController())->toPersian($this->created_at))[0],

        ];
    }
}
