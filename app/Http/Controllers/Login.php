<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;

class Login extends Controller
{
    
    public function login(){
        return view('login');
    }
    
    public function auth(Request $request){
        if($request->isMethod('post')){
            if (\Illuminate\Support\Facades\Auth::attempt(['usuario' => $request->get('usuario'), 'password' => $request->get('password')])){
                    return 'login '.$request->get('usuario');
            } else {
                return 'No logueado '.$request->get('usuario')." passs ".$request->get('password');
            }
        }else{
            return 'No se puede procesar no es post';
        }
    }
    
}
