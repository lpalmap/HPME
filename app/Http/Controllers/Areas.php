<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgAreaAtencion;

class Areas extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgAreaAtencion();
        $data=$metas->all(); 
        return view('areas',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CfgAreaAtencion::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgAreaAtencion::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgAreaAtencion::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgAreaAtencion::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion; 
        $item->orden=$request->orden;
        $item->orden_especial=$request->orden_especial;
        $item->save();
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        'orden'=> 'max:4'    
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}