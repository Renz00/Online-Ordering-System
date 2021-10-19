<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
  

class BaseController extends Controller
{

    // creating a unique order_group key
    public function setOrderGroup(){

        $rand_str = Str::random(10);
        $current_timestamp = Carbon::now()->timestamp; 

        $key = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999).$rand_str;

        // shuffle the result
        $group_id = str_shuffle($key).$current_timestamp;

        return $group_id;


    }

    public function explodeSlug($slug){
        //will create an array using the slug string
        $slugged = explode('-',$slug);
        //pulls the last value inside the slugged array
        $id = $slugged[array_key_last($slugged)];

        return $id;
    }

    //Custom paginator
    public function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');
        
        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
