<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\SegUsuario;

class Usuarios extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $usuarios=new SegUsuario();
        $data=$usuarios->all(); 
        return view('usuarios',array('usuarios'=>$data));
    }
    
    public function delete($id){
        $user = SegUsuario::destroy($id);
        return response()->json($user);
    }
    
    public function retrive($id){
        $user = SegUsuario::find($id);
        return response()->json($user);
    }
    
    public function add(Request $request){
        //$request->attributes->set('password', bcrypt($request->password));
        $data = $request->toArray();
        $data['password']=  bcrypt($data['password']);
        $user=  SegUsuario::create($data);
        return response()->json($user);
    }
    
    public function update(Request $request,$id){
        $user=  SegUsuario::find($id);
        $user->usuario=$request->usuario;
        if(strlen($request->password)>0){
            $user->password=  bcrypt($request->password);
        }
        $user->nombres=$request->nombres;
        $user->apellidos=$request->apellidos;
        $user->save();
        return response()->json($user);       
    }
    
}
