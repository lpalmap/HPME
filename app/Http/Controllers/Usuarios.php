<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $this->validateRequest($request);
        $data = $request->toArray();
        $data['password']=  bcrypt($data['password']);
        $user=  SegUsuario::create($data);
        return response()->json($user);
    }
    
    public function update(Request $request,$id){
        $this->validateRequestUpdate($request, $id);
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
    
    public function validateRequest($request){                
        $rules=[
            'usuario' => 'unique:seg_usuario|required|max:50',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'password' => 'required|max:200'    
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
            'apellidos' => 'required|max:100'  
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max',
            'unique' => 'El :attribute ya ha sido utilizado'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    
}
