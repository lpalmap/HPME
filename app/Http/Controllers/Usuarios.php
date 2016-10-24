<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SegUsuario;
use App\SegRol;

class Usuarios extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $usuarios=  SegUsuario::with("roles")->get(); 
        $roles=SegRol::all();
        return view('usuarios',array('usuarios'=>$usuarios,'roles'=>$roles));
    }
    
    public function delete($id){
        $user =  SegUsuario::find($id);
        $user->roles()->detach();
        SegUsuario::destroy($id);
        return response()->json($user);
    }
    
    public function retrive($id){
        $user = SegUsuario::find($id);
        $user->roles;
        return response()->json($user);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $data['password']=  bcrypt($data['password']);
        $user=  SegUsuario::create($data);
        if($request->ide_rol>0){
            $user->roles()->attach($request->ide_rol);
        }
        $user->roles;
        return response()->json($user);
    }
    
    public function update(Request $request,$id){
        $this->validateRequestUpdate($request, $id);
        $user=  SegUsuario::find($id);
        $user->usuario=$request->usuario;
        if(strlen($request->password)>0){
            $user->password=  bcrypt($request->password);
        }
        if($request->ide_rol>0){
            $user->roles()->sync([$request->ide_rol]);
        }else{
            $user->roles()->detach();
        }
        
        $user->nombres=$request->nombres;
        $user->apellidos=$request->apellidos;
        $user->email=$request->email;
        $user->save();
        $user->roles;
        return response()->json($user);       
    }
    
    public function validateRequest($request){                
        $rules=[
            'usuario' => 'unique:seg_usuario|required|max:50',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'password' => 'required|max:200',
            'email' => 'unique:seg_usuario|required|email|max:150'
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max',
            'unique' => 'El :attribute ya ha sido utilizado'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    public function validateRequestUpdate($request,$id){                
        
//        'email' => [
//                'required',
//                Rule::unique('users')->ignore($user->id),
//            ],
//        $rules=[
//            'usuario' => [
//                'required',
//                'max:50',
//                Rule::unique('seg_usuario')->ignore($id),
//            ],
//            'nombres' => 'required|max:100',
//            'apellidos' => 'required|max:100'  
//        ];
        $rules=[
            'usuario' => 'required|max:100|unique:seg_usuario,usuario,'.$id.',ide_usuario',
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
