<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient',
        'description',
        'notes'
    ];

    public function users(){
        //One address belongs to one user
        return $this->belongsTo(User::class);
    }

    

}
