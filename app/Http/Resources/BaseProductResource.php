<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sames = [];
        foreach ($this->sames as $item){
            if($item->id != $this->id){
                $sames[]=[
                    "id" => $item->id,
                    "title" => $item->title,
                    "slug" => str_replace(' ', '_', $item->title),
                    "price" => $item->products->min('price'),
                    "off" => $item->off,
                    "stock" => $item->products->sum('stock'),
                    "new" => $item->new,
                    "images" => $item->images
                ];
            }

        }
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => str_replace(' ', '_', $this->title),
            "made_in" => $this->made_in,
            "brand" => $this->brand,
            "price" => $this->products->min('price'),
            "off" => $this->off,
            "stock" => $this->products->sum('stock'),
            "sale" => $this->products->sum('sale'),
            "new" => $this->new,
            "images" => $this->images,
            "category_id" => $this->category_id,
            "products" => $this->products,
            "sames" => $sames
        ];
    }
}
