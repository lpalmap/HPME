<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;
use App\HPMEConstants;
use App\PlnAreaObjetivo;
use App\PlnIndicadorArea;
use Illuminate\Support\Facades\Auth;

class PlantillaPlanificacion extends Controller
{
    //Obtiene las plantillas de planificacion
    public function index(){
        $data=  PlnProyectoPlanificacion::with('periodicidad')->get(); 
        $periodos=  CfgListaValor::all()->where('grupo_lista', 'PERIODO_PLANIFICACION');
        $user=Auth::user();
        Log::info($user);
        $ideUsuario=$user->ide_usuario;
        Log::info("usuario logeado... ".$ideUsuario);
        Log::info("Session ".request()->session()->get("mi session"));
        //NOMBRE_ROL_POR_USUARIO
        $rol=  request()->session()->get('rol');
        return view('planificacionanual',array('items'=>$data,'periodos'=>$periodos,'rol'=>$rol));
    }
    
    public function addPlantilla(Request $request){
        $count=  PlnProyectoPlanificacion::where('estado',  HPMEConstants::ABIERTO)->count();
        if($count>0){
            $error=array('error'=>'Se encuentra una plantilla abierta debe cerrarla para crear una nueva.');
            return response()->json($error,  HPMEConstants::HTTP_AJAX_ERROR);
        }      
        $this->validateRequest($request);
        $descripcion=$request->descripcion;
        $periodicidad=$request->periodicidad;
        $plantilla=new PlnProyectoPlanificacion();
        $plantilla->descripcion=$descripcion;
        $plantilla->ide_lista_periodicidad=$periodicidad;     
        $plantilla->fecha_proyecto= date(HPMEConstants::DATE_FORMAT,  time());
        $authuser=Auth::user();
        $plantilla->ide_usuario_creacion=$authuser->ide_usuario;
        $plantilla->estado=  HPMEConstants::ABIERTO;
        Log::info("Antes de guardar");
        $plantilla->save();
        $plantilla->periodicidad;
        return response()->json($plantilla);
    }
    
    public function updatePlantilla(Request $request,$id){
        $this->validateRequestEditPlantilla($request);
        $item= PlnProyectoPlanificacion::find($id);
        $item->descripcion=$request->descripcion;       
        $item->save();
        $item->periodicidad;
        return response()->json($item);       
    }
    
    public function retrivePlantilla($id){
        $item = PlnProyectoPlanificacion::find($id);
        return response()->json($item);
    }
    
    public function deletePlantilla($ideProyecto){
        Log::info("Borando plantilla ".$ideProyecto);
        $item = PlnProyectoPlanificacion::destroy($ideProyecto);
        return response()->json($item);      
    }


//    public function addPlantilla(Request $request){
//        $listaItems=$request->items;
//        $ideAreaObjetivo=$request->ide_area_objetivo;
//        $areaObjetivo= PlnAreaObjetivo::find($ideAreaObjetivo);
//        Log::info("buscadon ".$ideAreaObjetivo);
//        $items=array();
//        foreach($listaItems as $item){
//            $nItem=new PlnIndicadorArea();
//            $nItem->ide_area_objetivo=$ideAreaObjetivo;
//            $nItem->ide_proyecto=$areaObjetivo->ide_proyecto;
//            $nItem->ide_meta=$areaObjetivo->ide_meta;
//            $nItem->ide_objetivo=$areaObjetivo->ide_objetivo;
//            $nItem->ide_area=$areaObjetivo->ide_area;
//            $nItem->ide_indicador=$item['ide_indicador'];
//            $nItem->save();
//            $nItem->indicador;
//            $items[]=$nItem;
//        }
//        return response()->json($items);
//    }

    public function validateRequest($request){
        $rules=[
        'descripcion' => 'required|max:250',
        'periodicidad' => 'required',
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }  
    
    public function validateRequestEditPlantilla($request){
        $rules=[
        'descripcion' => 'required|max:250'
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.'
        ];
        $this->validate($request, $rules,$messages);        
    } 
}