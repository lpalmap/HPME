<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgRecurso;

class Recursos extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgRecurso();
        $data=$metas->all(); 
        return view('recursos',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgRecurso::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgRecurso::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgRecurso::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgRecurso::find($id);
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