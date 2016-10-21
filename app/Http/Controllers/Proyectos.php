<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgProyecto;
use App\CfgRegion;
use Illuminate\Support\Facades\Log;
use App\HPMEConstants;

class Proyectos extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CfgProyecto();
        $data=$metas->all(); 
        return view('proyectos',array('items'=>$data));
    }
    
    public function delete($id){
        $proyecto = CfgProyecto::find($id);
        $proyecto->regiones()->detach();
        $item = CfgProyecto::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgProyecto::find($id);
        $item->regiones;
        $region = new CfgRegion();
        $params=array('ideProyecto'=>$id);
        $regiones=$region->selectQuery(HPMEConstants::REGIONES_PROYECTO_QUERY,$params);
        return response()->json(array('item'=>$item,'regiones'=>$regiones));
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgProyecto::create($data);
        $regiones=$request->regiones;
        $item->regiones()->sync($regiones);
        
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $item= CfgProyecto::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion; 
        $regiones=$request->regiones;
        Log::info("#### asta aca");
        $item->regiones()->sync($regiones);
        $item->save();
        return response()->json($item);       
    }
    
    public function retriveAllRegiones(Request $request){
        $regiones=  CfgRegion::all();
        return response()->json($regiones);
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