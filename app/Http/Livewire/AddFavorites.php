<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Favorites;
use Illuminate\Support\Facades\Auth;

class AddFavorites extends Component
{
    public $product_id;

    //The product collection that has been passed
    public $product;

    public function mount($product){
        $this->product_id = $product->id;
    }

    public function render()
    {
        $fav = Favorites::where('user_id', Auth::id())->where('product_id', $this->product_id)->get();

        return view('livewire.add-favorites', ['fav' => $fav]);
    }

    public function addFav(){

        $favs = new Favorites;

        $favs->user_id = Auth::id();
        $favs->product_id = $this->product_id;

        return $favs->save();   
    }

    public function removeFav(){

        $favs = Favorites::where('user_id', Auth::id())
            ->where('product_id', $this->product_id);

        return $favs->delete();   
    }

}
