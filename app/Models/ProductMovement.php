<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    protected $fillable = [
        'product_id', 'purchase_date', 'quantity',
        'movement_type', 'buy_price', 'sale_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}