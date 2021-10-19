<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends UsersController
{
    public function __construct(){

        $this->middleware('auth');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = $this->saveAddress($request);

        return redirect()->back()->with('message', $message);

    }
    
    public function getAddress($id){
        
        $address = Address::where('user_id', $id);
        //Will retrieve 1 row from address table
        return $address;
    }

    public function saveAddress(Request $request){

        //check if the user already has an address
            if (Address::where('user_id', Auth::id())->first()){

                $result = $this->updateAddress($request, Auth::id());

                if ($result != 1){
                    $message = 'Error: Changes are not properly saved.';

                    return $message;
                }
            }
            else {

                $address = new Address;

                $address->recipient = $request->input('recipient');
                $address->description = $request->input('address');
                $address->notes = $request->input('notes');
                $address->user_id = Auth::id();
    
                $address->save();   
                
            }

            $message = $this->savePhone($request->input('phone'));

            return $message;
    }
    

    public function updateAddress(Request $request, $id){

        $request->validate([
            'recipient' => 'required|max:255',
            'address' => 'required|max:500',
            'notes' => 'nullable|max:500'
        ]);
        
        $address = Address::where('user_id', $id)->update([
            'recipient' => $request->input('recipient'),
            'description' => $request->input('address'),
            'notes' => $request->input('notes')
        ]);
        
        return $address;

    }
}
