<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CfgColaborador;
use App\CfgDepartamento;
use App\HPMEConstants;

class Colaboradores extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $usuarios= CfgColaborador::with("departamento")->get(); 
        $roles=CfgDepartamento::all();
        return view('colaboradores',array('colaboradores'=>$usuarios,'departamentos'=>$roles));
    }
    
    public function delete($id){
        $user = CfgColaborador::find($id);
        CfgColaborador::destroy($id);
        return response()->json($user);
    }
    
    public function retrive($id){
        $user = CfgColaborador::find($id);
        $user->departamento;
        return response()->json($user);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        if($request->ide_departamento<=0){
            return response()->json(array('error'=>'Debe seleccionar un departamento para el colaborador'), HPMEConstants::HTTP_AJAX_ERROR );
        }
        $data = $request->toArray();
        $user= CfgColaborador::create($data);
        $user->departamento;
        return response()->json($user);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $user= CfgColaborador::find($id);
        if($request->ide_departamento<=0){
            return response()->json(array('error'=>'Debe seleccionar un departamento para el colaborador'), HPMEConstants::HTTP_AJAX_ERROR );
        }
        $user->ide_departamento=$request->ide_departamento;
        $user->nombres=$request->nombres;
        $user->apellidos=$request->apellidos;
        $user->save();
        $user->departamento;
        return response()->json($user);       
    }
    
    public function validateRequest($request){                
        $rules=[
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'ide_departamento' => 'required'
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max'
        ];
        $this->validate($request, $rules,$messages);        
    }
}