<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Orders;
use App\Models\Address;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

class UsersController extends BaseController
{
    public function __construct(){

        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('id', 'DESC')->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::where('id', $id)->get();
        $address = Address::where('user_id', $id)->get();

        $response = [
            'users' => $users,
            'address' => $address
        ];

        return response($response, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        $id = $this->explodeSlug($id);

        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }

        if ($type == 'account'){

            if (Auth::user()->address_id != null){

                $user = DB::table('addresses')
                    ->join('users', 'addresses.id', '=', 'users.address_id')
                    ->where('users.id', $id)
                    ->get();

            }
            else {
                $user = User::where('id', $id);
            }

            return view('pages/account')->with('user', $user);
        }
        elseif ($type == 'address'){

            $address = Address::where('user_id', $id);

            return view('pages/address')->with('address', $address);
        }
        else {
            return redirect()->back();
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $this->explodeSlug($id);

        //Prevents users from accessing other user's records
        if (Auth::id() != $id){
            return redirect()->back();
        }

        //checking which submit button has been pressed
        switch ($request->submit_button) {

            case 'save':
                $request->validate([
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'phone' => ['nullable', 'regex:/(09)[0-9]{9}/'],
                    'profile_image' => 'image|nullable|max:1999'
                ]);

                $this->saveUserUpdate($request, $id);
            break;

            case 'password':
                $request->validate([
                    'current_password' => ['required', 'string', 'min:8'],
                    'password' => ['required', 'string', 'min:8', 'confirmed']
                ]);
                //password verification method
                $result = $this->changePassword($request, $id);

                if ($result != 'updated'){
                    return redirect()->back()->with('error', '- Password does not exist -');
                }  

            break;
            
            case 'delete':

                $request->validate([
                    'user_password' => ['required', 'string', 'min:8'],
                    'user_password_confirmation' => ['required', 'same:user_password']
                ],
                [
                    'user_password.required' => 'Password is required.',
                    'user_password_confirmation.same' => 'Password confirmation does not match.',
                    'user_password.min' => 'Password must have at least 8 characters.'
                ]);

                $result = $this->passwordCheck($request->user_password);

                //checking if the current password exists
                if ($result == true){

                    $password = 
                    $msg = $this->destroy($id);

                    if ($msg != 1){
                        return redirect()->back()->with('error', '- User cannot be deleted. -');
                    }

                    $this->userLogout($request->user_password);
    
                } 
                else {
                    return redirect()->back()->with('error', '- Password does not exist -');
                }
                
                

            break;
        }

        return redirect()->back()->with('message', 'success');
    }

    public function saveUserUpdate(Request $request, $id){
        $user = User::find($id);

        if($request->hasFile('profile_image')){
            $filename = $this->userImage($request, $id);

            if ($user->image != 'noimage.png'){
                $this->deleteImage($user->image);
            }
        }
        else {
            if ($user->image != 'noimage.png'){
                $filename = $user->image;
            }
            else {
                $filename = 'noimage.png';
            }
        }

        if ($request->username != null && $request->username != ''){
            $user->username = $request->username;
        }
        
        $user->role = $request->role;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->image = $filename;

        $result = $user->save();

        //Checking if user already has an address
        $useradd = Address::where('user_id', $id)->first();
        
        if (empty($useradd)){
            if ($request->description != null && $request->description != ''){
                $address = new Address;
                $address->recipient = $request->first_name.' '.$request->last_name;
                $address->description = $request->description;
                $address->notes = $request->notes;
                $address->user_id = $id;
    
                $address->save();   
            }
        }
        else {
            $useradd->description = $request->description;
            $useradd->notes = $request->notes;
            $useradd->save();
        }
     
        if ($result == 1){
            return 'updated';
        }
        else {
            return 'Error';
        }
    }

    public function changePassword(Request $request, $id){

        $user = User::find($id);

        if (Hash::check($request->current_password, $user->password)){
            User::find($id)->update([
                'password' => Hash::make($request->password)
            ]);
            return 'updated';
 
         } else {
             return 'Error';
         }  
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $user = User::find($id);

        if ($user->image != 'noimage.png'){
            $this->deleteImage($user->image);
        }

        return $user->delete(); 
     
    }

    public function savePhone($phone){

        try{
            $user = User::find(Auth::id());
        
            $user->phone = $phone;

            $user->save();
            
            return 'success';
            
        } catch (Excception $e){
            return 'Error: Changes are not properly saved.';
        }
    
        
    }

    public function userImage(Request $request, $id){
        //Handle File upload
       
        //Get file name with extension
        $filenameWithExt = $request->file('profile_image')->getClientOriginalName();
        //Get file name only
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //Get file extension only
        $extension = $request->file('profile_image')->getClientOriginalExtension();
        //File name to store
        $filenameToStore = $filename.'_'.$id.'_'.time().'.'.$extension;
        //Upload image to folder
        $path = $request->file('profile_image')->storeAs('public/profile_images', $filenameToStore);

        return $filenameToStore;
    }

    public function deleteImage($image){
        File::delete(public_path('/storage/profile_images/'.$image));
        return;
    }

    public function userLogout($password){
        Session::flush();
        Auth::logoutOtherDevices($password);
        return view('auth/login');
    }


}
