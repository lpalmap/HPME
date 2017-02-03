<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SegRol;
use Illuminate\Support\Facades\Log;

class Privilegios extends Controller
{
    //
    //Se cargan los roles y los privilegios asociados a cada uno
    public function index(){
        $roles=SegRol::with('privilegios')->get();
//        Log::info('Privilegios?');
//        foreach ($roles as $rol){
//            Log::info('Rol: '.$rol->descripcion);
//            Log::info($rol->privilegios);
//        }
        
        return view('privilegios',array('items'=>$roles));
    }
    
    public function retrivePrivilegios($id){
        $rol= SegRol::find($id);
        $rol->privilegios;
        return response()->json($rol);
    }
    
    public function delete($id){
        $item = SegRol::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = SegRol::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  SegRol::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= SegRol::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion;        
        $item->save();
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}