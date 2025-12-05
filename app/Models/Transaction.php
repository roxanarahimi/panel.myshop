<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'user_id','id' );
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class , 'order_id','id');
    }
}
