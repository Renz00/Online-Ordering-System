<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends BaseController
{

    public function error(){
        return view('pages/errors/session_error');
    }

    public function contact(){
        return view('pages/contact');
    }

    public function dashboard(){
        return view('vue/layout');
    }


}
