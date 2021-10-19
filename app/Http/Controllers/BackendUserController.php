<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\Hello;
use App\Models\Address;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\DashboardEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BackendUserController extends Controller
{

    public function login(Request $request){

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

            $user = User::where('username', $request->username)->first();

            $token = $user->createToken('user-token')->plainTextToken; //create a token for sanctum authorization

            $response = [
                'user' => $user,
                'token' => $token
            ];

            if ($request->role == 'Customer'){
                DashboardEvent::dispatch();
            }

            return response($response, 201);
        }
        return "Error";
    }

    public function btest(){
        $data = [
            'message' => 'hello'
        ];
       DashboardEvent::dispatch($data);
    }

    public function checkUnique(Request $request){

        if ($request->type == 'update'){
            $validate = User::where('username', $request->username)->where('username', '<>', null)->where('id', '<>', $request->id)->get();
        }
        else {
            $validate = User::where('username', $request->username)->get();
        }
        if (count($validate) > 0){ //username exists
            return "exists";
        }
        else {
            return "unique";
        }
      
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'response' => 'success'
        ];
    }

    
    public function test(){
        return "this is protected";
    }

    public function getName(){
        return User::latest()->first();
    }

    //Edits a excel template inside public/assets and download it
    public function print(){ 
        $ss = IOFactory::load('assets/report_temp.xlsx'); //retrieves the excel template
        $ss->getActiveSheet()->setCellValue('A2', 'Renz'); //sets the specified cell's value
        $filename = 'test.xlsx';

        $writer = new Xlsx($ss);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Cache-Control','max-age=0');
        return $response;   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user = new User;
        $user->username = $request->username;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->image = 'noimage.png';

        $result = $user->save();

        if ($request->description != null && $request->description != ''){
            $address = new Address;

            $address->recipient = $request->firstname.' '.$request->lastname;
            $address->description = $request->description;
            $address->notes = $request->notes;
            $address->user_id = $user->id;

            $address->save();   
        }

        if ($result == 1){

            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
    
                $token = $user->createToken('user-token')->plainTextToken; //create a token for sanctum authorization
    
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
    
                return response($response, 201);
            }
        }
        else {
            return 'Error';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BackendUser  $backendUser
     * @return \Illuminate\Http\Response
     */
    public function show(BackendUser $backendUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BackendUser  $backendUser
     * @return \Illuminate\Http\Response
     */
    public function edit(BackendUser $backendUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BackendUser  $backendUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BackendUser $backendUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BackendUser  $backendUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(BackendUser $backendUser)
    {
        //
    }

}
