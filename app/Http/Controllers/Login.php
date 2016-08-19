<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Login extends Controller
{
    public function index($name){
        return view('login',array('name'=>$name));
    }
}
