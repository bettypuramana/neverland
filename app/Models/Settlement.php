<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'category_id',
        'settled',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

