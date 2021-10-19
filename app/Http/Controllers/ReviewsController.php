<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;
use App\Events\DashboardEvent;
use Illuminate\Support\Facades\DB;

class ReviewsController extends Controller
{
    public function index(){
        return DB::table('users')
        ->join('reviews', 'users.id', '=', 'reviews.user_id')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->select('reviews.id', 'reviews.rating','users.first_name', 'users.last_name', 'products.name','reviews.created_at')
        ->orderBy('reviews.id', 'DESC')
        ->get();
    }

    public function show($id){
        return DB::table('users')
        ->join('reviews', 'users.id', '=', 'reviews.user_id')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->select('reviews.id', 'reviews.rating','reviews.content','users.first_name', 'users.last_name', 'products.name','reviews.created_at')
        ->where('reviews.id', $id)
        ->get();
    }

    public function destroy($id){

        $review = Reviews::find($id);
        $result = $review->delete();

        if ($result == 1){
            DashboardEvent::dispatch(); //Refresh admin dashboard
            return 'deleted';
        }
        else {
            return 'Error';
        }

    }
}
