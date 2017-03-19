<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SegUsuario;
use App\HPMEConstants;
use Illuminate\Support\Facades\Log;

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
                $user=Auth::user();
                $queryUser=new SegUsuario();
                //Log::info('Usuario.... '+$user->ide_usuario);
                $params=array('ideUsuario'=>$user->ide_usuario);
                $roles=$queryUser->selectQuery(HPMEConstants::NOMBRE_ROL_POR_USUARIO, $params);
                //Log::info($roles);
                $rol='';
                if(count($roles)>0){
                    $rol=$roles[0]->nombre;
                }
                $priv= $queryUser->selectQuery(HPMEConstants::PRIVILEGIOS_POR_USUARIO, $params);
                $privilegios=array();
                foreach($priv as $privilegio){
                    $privilegios[]=$privilegio->ide_privilegio;
                }
                $request->session()->put('privilegios',$privilegios);
               // Log::info("#### privilegios");
                //Log::info('rol.... '.$rol);
                $request->session()->put("rol", $rol);
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
