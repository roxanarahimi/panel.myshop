<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "base_product_id" => $this->base_product_id,
            "size" => $this->size,
            "info" => $this->info,
            "price" => min($this->price,$this->info->price),
            "off" => max($this->off,$this->info->off),
            "category_id" => $this->info->category_id,
        ];
    }
}
