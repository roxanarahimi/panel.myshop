<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaseProduct extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class ,'category_id','id');
    }
    public function sames(): HasMany
    {
        return $this->hasMany(BaseProduct::class ,'category_id','category_id')->take(4);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class , 'base_product_id','id');
    }

    protected $casts = [
        'images' => 'array',
    ];
}
