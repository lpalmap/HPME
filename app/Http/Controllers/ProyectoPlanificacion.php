<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgProducto;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;

class ProyectoPlanificacion extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $proyecto=new PlnProyectoPlanificacion();
        $data=$proyecto->all(); 
        $periodos=  CfgListaValor::all()->where('grupo_lista', 'PERIODO_PLANIFICACION');
        return view('planificacionanual',array('items'=>$data),array('periodos'=>$periodos));
    }
    
    public function metas(){
        return view('planificacionmetas');
    }
    
    public function objetivos(){
        return view('planificacionobjetivos');
    }
    
    public function areas(){
        return view('planificacionarea');
    }
    
    public function indicadores(){
        return view('planificacionindicadores');
    }
    
    public function productos(){
        return view('planificacionproductos');
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