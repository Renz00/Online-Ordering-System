<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Best extends Model
{
    use HasFactory;

    public function products(){
        //One best belongs to one product
        return $this->belongsTo(Products::class);
    }

}
