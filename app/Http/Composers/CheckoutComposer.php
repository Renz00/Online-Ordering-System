<?php

namespace App\Http\Composers;

use App\Models\Orders;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutComposer{
    // This will update a variable used in every view, useful for navbar/sidebar elements that needs to update
    // This needs a custom Service Provider to work
    public function compose(View $view){
        //Setting session values and getting order count for layouts/partials/checkout.blade.php
        $order_count = 0;

        if (Auth::check()){
            $orders = Orders::where('user_id', Auth::id())->where('status', null);

            //checks if there are orders in table
            if ($orders->count()){
                $order_count = $orders->count();
            }
        }

        //loads current view with order_count variable
        $view->with('order_count', $order_count);
    }

}