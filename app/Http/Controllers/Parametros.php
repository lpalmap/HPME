<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgParametro;

class Parametros extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgParametro();
        $data=$metas->all(); 
        return view('parametros',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgParametro::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgParametro::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgParametro::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgParametro::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion; 
        $item->valor=$request->valor;
        $item->save();
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'valor' => 'required|max:100',
        'descripcion' => 'required|max:250',
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}