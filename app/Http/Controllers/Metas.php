<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgMeta;

class Metas extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $data= CfgMeta::all(); 
        return view('metas',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgMeta::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgMeta::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgMeta::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgMeta::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion;  
        $item->orden=$request->orden;
        $item->save();
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        'orden'=>'max:4'   
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}
