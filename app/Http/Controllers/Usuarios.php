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
        
    }
    
    public function update(Request $request,$id){
        
    }
    
}