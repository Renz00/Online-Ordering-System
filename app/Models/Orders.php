<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'amount',
    ];

    public function products(){
        return $this->belongsTo(Product::class);
    }

    public function OrderAddress(){
        return $this->belongsTo(OrderAddress::class);
    }
}
