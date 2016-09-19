<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgListaValor;

class Listas extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgListaValor();
        $data=$metas->all(); 
        $grupos=$this->grupoLista();
        return view('listas',array('items'=>$data,'grupos'=>$grupos));
    }
    
    public function delete($id){
        $item = CfgListaValor::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgListaValor::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgListaValor::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgListaValor::find($id);
        $item->grupo_lista=$request->grupo_lista;
        $item->descripcion=$request->descripcion; 
        $item->codigo_lista=$request->codigo_lista;
        $item->save();
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'grupo_lista' => 'required|max:100',
        'codigo_lista' => 'required|max:100',
        'descripcion' => 'required|max:250',
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }  
    
    public function grupoLista(){
        return [
            'PERIODO_PLANIFICACION',
            'MESES',
            'TRIMESTRES'
        ];
    }
}