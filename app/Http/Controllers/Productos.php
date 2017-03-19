<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgProducto;

class Productos extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgProducto();
        $data=$metas->all(); 
        return view('productos',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgProducto::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgProducto::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgProducto::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgProducto::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion; 
        $item->orden=$request->orden;
        $item->requiere_comprobantes=$request->requiere_comprobantes;
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