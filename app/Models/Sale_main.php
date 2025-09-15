<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_main extends Model
{
    use HasFactory;

    public function getCustomer(){
        return $this->HasOne(Customer::class,'id','customer_id');
    }
    public function allItems()
    {
        return $this->hasMany(Sale_sub::class, 'sale_main_id', 'id');
    }
    public function getRentItemCountAttribute()
    {
        return $this->allItems()->where('item_type', 'rent')->count();
    }
    public function getRentItems()
    {
        return $this->allItems()->where('item_type', 'rent')->get();
    }
}
