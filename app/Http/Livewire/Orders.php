<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\order_group;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search='';
    public $status='All';
    public $msg='';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!empty($this->search)){
            $order_groups = order_group::where('user_id', Auth::id())
            ->where('id', 'LIKE', '%'.$this->search.'%')
            ->latest()
            ->paginate(8);

        }
        elseif ($this->status == 'All'){
            $order_groups = order_group::where('user_id', Auth::id())
            ->where('status', '<>', 'Cancelled')
            ->where('status', '<>', null)
            ->latest()
            ->paginate(8);
        } else {
             $order_groups = order_group::where('user_id', Auth::id())
             ->where('status', '=', $this->status)
             ->latest()
             ->paginate(8);
            
        }

        return view('livewire.orders', ['orders' => $order_groups]);
    }

    public function changeStatus($order_status){

        $this->status=$order_status;
        $this->search ='';
    }

    public function gotoOrderDetails($order_group){
        return redirect()->route('orders.details', $order_group);
    }


}
