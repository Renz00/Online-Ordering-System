<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Products;
use Livewire\WithPagination;

class Menu extends Component
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
            
            $products = Products::when($this->search, function($query, $search){
                return $query->where('name', 'LIKE', "%$search%")->orWhere('type', 'LIKE', "%$search%");
            })->paginate(12);
        }
        elseif ($this->category == 'All'){
            $products = Products::paginate(12);
        }
        elseif ($this->category == 'Discount'){
            $products = Products::where('discount', '<>', 0)->paginate(12);
        }
        else {
            $products = Products::where('type', '=', $this->category)->paginate(12);
        }
        
        return view('livewire.menu', ['products' => $products]);
    }
    

}
