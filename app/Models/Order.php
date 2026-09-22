<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'user_id','id' );
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class , 'order_id','id');
    }
    public function address(): HasOne
    {
        return $this->hasOne(Address::class , 'id','address_id');
    }
}
