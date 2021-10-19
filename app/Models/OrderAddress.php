<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    public function orders(){
        //One address belongs to many orders
        return $this->hasMany(Orders::class);
    }
}
