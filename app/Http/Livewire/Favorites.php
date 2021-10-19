<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Products;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Favorites extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $category = 'All';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    //Dynamic search functionality
    public function render()
    {
        if (!empty($this->search)){

            $favs = DB::table('products')
                ->join('favorites', 'products.id', '=', 'favorites.product_id')
                ->where('favorites.user_id', Auth::id())
                ->where('products.name', 'LIKE', '%'.$this->search.'%')
                ->latest('favorites.id')
                ->paginate(12);

        }
        elseif ($this->category == 'All'){
            $favs = DB::table('products')
                ->join('favorites', 'products.id', '=', 'favorites.product_id')
                ->where('favorites.user_id', Auth::id())
                ->latest('favorites.id')
                ->paginate(12);
        }
        elseif ($this->category == 'Discount'){
            $favs = DB::table('products')
                ->join('favorites', 'products.id', '=', 'favorites.product_id')
                ->where('favorites.user_id', Auth::id())
                ->where('products.discount', '<>', 0)
                ->latest('favorites.id')
                ->paginate(12);
        }
        else {
            $favs = DB::table('products')
                ->join('favorites', 'products.id', '=', 'favorites.product_id')
                ->where('favorites.user_id', Auth::id())
                ->where('products.type', '=', $this->category)
                ->latest('favorites.id')
                ->paginate(12);
        }
        
        return view('livewire.favorites', ['products' => $favs]);
    }
    

}
