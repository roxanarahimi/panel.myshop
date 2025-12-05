<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    public function info(): BelongsTo
    {
        return $this->belongsTo(BaseProduct::class ,'base_product_id','id' );
    }
}
