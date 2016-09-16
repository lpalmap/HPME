<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgObjetivo;

class Objetivos extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgObjetivo();
        $data=$metas->all(); 
        return view('objetivos',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgObjetivo::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgObjetivo::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgObjetivo::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgObjetivo::find($id);
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