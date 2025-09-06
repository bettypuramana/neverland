<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'type', 'buy_price', 'sale_price'];

    public function movements()
    {
        return $this->hasMany(ProductMovement::class);
    }
}
