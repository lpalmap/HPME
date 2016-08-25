<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    
    public function login($error=null){
        return view('login',array('error'=>$error));
//        if($nombre=null){
//            return view('login');
//        }else{
//            return view('login',array('nombre'=>$nombre));
//        }    
    }
    
    public function auth(Request $request){
        if($request->isMethod('post')){
            if (Auth::attempt(['usuario' => $request->get('usuario'), 'password' => $request->get('password')],$request->get('remember'))){
                //return redirect()->route('home',array('usuario'=>$request->get('usuario')));
                return redirect()->route('home');
            
                //return redirect()->route('usuarios');
            } else {
                //return 'No logueado '.$request->get('usuario')." passs ".$request->get('password');
                return redirect()->route('login',array("error"=>"error"));
                
            }
        }else{
            return 'No se puede procesar no es post';
        }
    }
    
    public function logout(){
        Auth::logout();
        return view('login');
    }
    
}
