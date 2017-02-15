<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;
use App\HPMEConstants;
use App\PlnProyectoPresupuesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CfgRegion;
use App\PlnProyectoRegion;
use App\PrivilegiosConstants;

class PlantillaPlanificacion extends Controller
{
    //Obtiene las plantillas de planificacion
    public function index(){
        $data=  PlnProyectoPlanificacion::with('periodicidad')->get(); 
        $periodos=  CfgListaValor::all()->where('grupo_lista', 'PERIODO_PLANIFICACION');
        //$user=Auth::user();
        //Log::info($user);
        //$ideUsuario=$user->ide_usuario;
        //Log::info("usuario logeado... ".$ideUsuario);
        //Log::info("Session ".request()->session()->get("mi session"));
        //NOMBRE_ROL_POR_USUARIO
        $rol=  request()->session()->get('rol');
        $ingresaPlan=$this->ingresoPlanificacion();
        if($ingresaPlan){
            $ideProyecto=null;
            foreach ($data as $proyecto){
                if($proyecto['estado']===HPMEConstants::PUBLICADO){
                    $ideProyecto=$proyecto['ide_proyecto'];
                    break;
                }
            }
            //Log::info("buscando ide_proyecto $ideProyecto");
            $region=$this->regionUsuario();
            if(!is_null($region) && !is_null($ideProyecto)){
                $regionProyecto=PlnProyectoRegion::where(array("ide_region"=>$region,"ide_proyecto_planificacion"=>$ideProyecto))->pluck('estado')->first(); 
                if(!is_null($regionProyecto)){
                    return view('planificacionanual',array('items'=>$data,'periodos'=>$periodos,'rol'=>$rol,'estadoRegion'=>$regionProyecto,'ingresaPlan'=>$ingresaPlan));
                }
                
            }
            
        }
        return view('planificacionanual',array('items'=>$data,'periodos'=>$periodos,'rol'=>$rol,'ingresaPlan'=>$ingresaPlan));
    }
    
    private function ingresoPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFIACION_INGRESAR_PLANIFICACION, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
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
        date_default_timezone_set(HPMEConstants::TIME_ZONE);
        $plantilla->fecha_proyecto= date(HPMEConstants::DATE_FORMAT,  time());
        $authuser=Auth::user();
        $plantilla->ide_usuario_creacion=$authuser->ide_usuario;
        $plantilla->estado=  HPMEConstants::ABIERTO;
        //Log::info("Antes de guardar");
        $plantilla->save();
        $plantilla->periodicidad;
        //Crea el proyecto de presupuesto al momento de crear la plantilla
        $this->crearProyectoPresupuesto($plantilla);
        return response()->json($plantilla);
    }
    
    private function crearProyectoPresupuesto(PlnProyectoPlanificacion $p){
        //Log::info($p);
        $presupuesto=new PlnProyectoPresupuesto;
        $presupuesto->fecha_proyecto=$p->fecha_proyecto;
        $presupuesto->descripcion=$p->descripcion;
        $presupuesto->estado=$p->estado;
        $presupuesto->ide_proyecto_planificacion=$p->ide_proyecto;
        $presupuesto->create($presupuesto->toArray());
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
        //Log::info("Borando plantilla ".$ideProyecto);
        //Se busca si la plantilla tiene un proyecto de presupusto para eliminarlo junto a la plantilla.
        $presupuesto=DB::select(HPMEConstants::PLN_PROYECTO_PRESUPUESTO_POR_PLANIFICACION,array('ideProyecto'=>$ideProyecto));
        if(count($presupuesto)>0){
            //Log::info($presupuesto[0]->ide_proyecto_presupuesto);
            PlnProyectoPresupuesto::destroy($presupuesto[0]->ide_proyecto_presupuesto);
        }       
        $item = PlnProyectoPlanificacion::destroy($ideProyecto);
        return response()->json($item);      
    }
    
    public function publicarPlantilla(Request $request){
        $rol=  request()->session()->get('rol');
        if($rol!='COORDINADOR'){
            return response()->json(array('error'=>'Solo el usuario autorizado puede publicar una plantilla.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $proyecto=  PlnProyectoPlanificacion::find($request->ide_proyecto);
        if($proyecto->estado==HPMEConstants::ABIERTO){
            $proyecto->estado=  HPMEConstants::PUBLICADO;
            $proyecto->save();
            $presupuesto=DB::select(HPMEConstants::PLN_PROYECTO_PRESUPUESTO_POR_PLANIFICACION,array('ideProyecto'=>$proyecto->ide_proyecto));
            if(count($presupuesto)>0){
                //Log::info($presupuesto[0]->ide_proyecto_presupuesto);
                $proyectoPresupuesto=PlnProyectoPresupuesto::find($presupuesto[0]->ide_proyecto_presupuesto);
                $proyectoPresupuesto->estado=  HPMEConstants::PUBLICADO;
                $proyectoPresupuesto->save();
            } 
            return response()->json(array('sts'=>'OK'));
        }else{
            return response()->json(array('error'=>'Solo se pueden publicar plantillas abiertas.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        
    }
    
    public function cerrarPlanificacion(Request $request){
        $rol=  request()->session()->get('rol');
        if($rol!='COORDINADOR'){
            return response()->json(array('error'=>'Solo el usuario autorizado puede cerrar una plantilla.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $proyecto=  PlnProyectoPlanificacion::find($request->ide_proyecto);
        if($proyecto->estado!=HPMEConstants::PUBLICADO){
            return response()->json(array('error'=>'La plantilla debe estar '.HPMEConstants::PUBLICADO.' para cerrarla.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $count=  PlnProyectoRegion::where([['ide_proyecto_planificacion','=',$request->ide_proyecto],['estado','!=',  HPMEConstants::APROBADO]])->count();
        if($count>0){
            return response()->json(array('error'=>"Se encuentran $count planificaciones por regi&oacute;n pendientes de aprobar."), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $proyecto->estado=  HPMEConstants::CERRADO;
        date_default_timezone_set(HPMEConstants::TIME_ZONE);
        $proyecto->fecha_cierre=date(HPMEConstants::DATE_FORMAT,  time());
        $proyecto->save();
        return response()->json();
    }
    
    public function enviarPlantilla(Request $request){
        $rol=  request()->session()->get('rol');
        $ingresaPlan=$this->ingresoPlanificacion();   
        if(!$ingresaPlan){
            return response()->json(array('error'=>'Solo los adminitradores de una regi&oacute;n enviar plantillas.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $proyecto=  PlnProyectoPlanificacion::find($request->ide_proyecto);
        if($proyecto->estado!=HPMEConstants::PUBLICADO){
            return response()->json(array('error'=>'Solo se pueden enviar plantillas si el proyecto esta PUBLICADO.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $region=$this->regionUsuario();
        if(!is_null($region)){
            $ideProyectoRegion=PlnProyectoRegion::where(array("ide_region"=>$region,"ide_proyecto_planificacion"=>$request->ide_proyecto))->pluck('ide_proyecto_region')->first(); 
            if(!is_null($ideProyectoRegion)){
                $proyectoRegion=  PlnProyectoRegion::find($ideProyectoRegion);
                if($proyectoRegion->estado!='ABIERTO'){
                    return response()->json(array('error'=>'Solo se pueden enviar plantillas si el proyecto esta '.HPMEConstants::ABIERTO), HPMEConstants::HTTP_AJAX_ERROR);
                }
                $metasIncompletas=DB::select(HPMEConstants::PLN_METAS_NO_INGRESADAS,array('ideProyectoRegion'=>$ideProyectoRegion));
                if(count($metasIncompletas)>0){
                    $errores=array();
                    foreach($metasIncompletas as $meta){
                        //Log::info($meta['nombre']);
                        $errores[]='Meta obligatoria incompleta '.$meta->nombre.'.';
                    }
                    return response()->json($errores,HPMEConstants::HTTP_AJAX_ERROR);
                }else{
                    $proyectoRegion->estado=  HPMEConstants::ENVIADO;
                    $proyectoRegion->save();
                    return response()->json();
                }                
            }
        }     
    }
    
    private function regionUsuario(){
        $user=Auth::user();
        $regionQuery=new CfgRegion();
        $regiones=$regionQuery->selectQuery(HPMEConstants::REGION_USUARIO_ADMINISTRADOR_QUERY, array('ideUsuario'=>$user->ide_usuario));
        if(count($regiones)>0){
            return $regiones[0]->ide_region;           
        }else{
            return null;
        }
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

    public function cerrarPlantilla(Request $request){
        $proyectoPlanificacion=  PlnProyectoPlanificacion::find($request->ide_proyecto);
        if($proyectoPlanificacion->estado!=HPMEConstants::ABIERTO){
            
        }
    }
    
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