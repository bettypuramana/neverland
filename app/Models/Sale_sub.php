<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_sub extends Model
{
    use HasFactory;

    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }
}
