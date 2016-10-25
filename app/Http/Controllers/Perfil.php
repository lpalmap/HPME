<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SegUsuario;
use Illuminate\Support\Facades\Log;

class Perfil extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $user=Auth::user();
        $ideUsuario=$user->ide_usuario;
        $usuario=  SegUsuario::find($ideUsuario);
        $usuario->roles;
        return view('perfil',array('usuario'=>$usuario));
    }
    
    public function update(Request $request){
        Log::info('test adfsdf');
        $usuario=Auth::user();
        $id=$usuario->ide_usuario;
        $this->validateRequestUpdate($request, $id);
        $user=  SegUsuario::find($id);
        if(strlen($request->password)>0){
            $user->password=  bcrypt($request->password);
        }
        $user->nombres=$request->nombres;
        $user->apellidos=$request->apellidos;
        $user->email=$request->email;
        $user->save();
        $user->roles;
        return response()->json($user);       
    }
        
    public function validateRequestUpdate($request,$id){                
        $rules=[
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'email' => 'required|email|max:150|unique:seg_usuario,email,'.$id.',ide_usuario'
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max',
            'unique' => 'El :attribute ya ha sido utilizado'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    
}
