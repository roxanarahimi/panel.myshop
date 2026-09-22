<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        switch ($this->status){
            case 'cart':$status='سبد خرید';break;
            case 'payed':$status='ثبت شد';break;
            case 'in progress':$status='آماده سازی';break;
            case 'ready to send':$status='آماده ارسال';break;
            case 'sent':$status='ارسال شد';break;
            case 'delivered':$status='دریافت شد';break;
            case 'canceled':$status='کنسل شد';break;
        }
        return [
            'id' => $this->id,
            'code' =>  $this->code,
            'type' =>  $this->type,
            'user' =>  $this->user,
            'address' => new AddressResource($this->address),
            'items' => OrderItemResource::collection($this->items),
            'sum' => $this->items->SUM('quantity'),
            'total_amount' => $this->total_amount,
            'total_off' => $this->total_off,
            'delivery_amount' => $this->delivery_amount,
            'amount' => $this->amount,
            'status' => $status,
            'payed_at' => explode(' ', (new DateController())->toPersian($this->payed_at))[0],
            'created_at' => explode(' ', (new DateController())->toPersian($this->created_at))[0],

        ];
    }
}
