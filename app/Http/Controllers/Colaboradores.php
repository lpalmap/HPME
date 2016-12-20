<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CfgColaboradorProyecto;
use App\CfgDepartamento;
use App\HPMEConstants;
use App\CfgPuesto;

class Colaboradores extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $usuarios= CfgColaboradorProyecto::with('departamento','puesto')->get(); 
        $roles=CfgDepartamento::all();
        $puestos=CfgPuesto::all();
        return view('colaboradores',array('colaboradores'=>$usuarios,'departamentos'=>$roles,'puestos'=>$puestos));
    }
    
    public function delete($id){
        $user = CfgColaboradorProyecto::find($id);
        CfgColaboradorProyecto::destroy($id);
        return response()->json($user);
    }
    
    public function retrive($id){
        $colaborador = CfgColaboradorProyecto::find($id);
        $colaborador->departamento;
        $colaborador->puesto;
        return response()->json($colaborador);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        if($request->ide_departamento<=0){
            return response()->json(array('error'=>'Debe seleccionar un departamento para el colaborador/proyecto'), HPMEConstants::HTTP_AJAX_ERROR );
        }
        if($request->tipo==HPMEConstants::COLABORADOR && $request->ide_puesto<=0){
            return response()->json(array('error'=>'Debe seleccionar un puesto para el colaborador'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $data = $request->toArray();
        $user= CfgColaboradorProyecto::create($data);
        $user->departamento;
        $user->puesto;
        return response()->json($user);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $colaborador= CfgColaboradorProyecto::find($id);
        if($request->ide_departamento<=0){
            return response()->json(array('error'=>'Debe seleccionar un departamento para el colaborador/proyecto'), HPMEConstants::HTTP_AJAX_ERROR );
        }
        if($request->tipo==HPMEConstants::COLABORADOR && $request->ide_puesto<=0){
            return response()->json(array('error'=>'Debe seleccionar un puesto para el colaborador'), HPMEConstants::HTTP_AJAX_ERROR);
        }       
        $colaborador->ide_departamento=$request->ide_departamento;
        $colaborador->nombres=$request->nombres;
        $colaborador->apellidos=$request->apellidos;
        
        if($request->tipo==HPMEConstants::COLABORADOR){
            $colaborador->ide_puesto=$request->ide_puesto;
        }else{
            $colaborador->ide_puesto=NULL;
        }
        
        $colaborador->save();
        $colaborador->departamento;
        $colaborador->puesto;
        return response()->json($colaborador);       
    }
    
    public function validateRequest($request){                
        
        $rules=array();
        if($request->tipo==HPMEConstants::COLABORADOR){
            $rules=[
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'ide_departamento' => 'required',
            'ide_puesto'=>'required'
            ];
        }else{
            $rules=[
            'nombres' => 'required|max:100',
            'ide_departamento' => 'required'
            ];
        }
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max'
        ];
        $this->validate($request, $rules,$messages);        
    }
}