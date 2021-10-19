<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderAddressController extends AddressController
{
    public function __construct(){

        $this->middleware('auth');

    }

    public function getOrderAddress($group_id){

        return OrderAddress::where('order_group_id', $group_id)->get();
    }
    
    public function saveOrderAddress(Request $request, $group_id, $id){

       
            $order_add = new OrderAddress;

            $order_add->order_group_id = $group_id;
            $order_add->user_id = $id;
            $order_add->recipient = $request->input('recipient');
            $order_add->phone = $request->input('phone');
            $order_add->description = $request->input('address');
            $order_add->notes = $request->input('notes');


            $order_add->save();

            $message = $this->saveAddress($request);

            return $message;

        
    }

    // public function destroyOrderAddress($group_id){
    //     return OrderAddress::where('order_group_id', $group_id)->delete();
    // }
}
