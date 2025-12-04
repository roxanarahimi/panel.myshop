<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class , 'id','user_id');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class , 'id','order_id');
    }
}
