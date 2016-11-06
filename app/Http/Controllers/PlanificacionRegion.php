<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\HPMEConstants;
use App\PlnProyectoPlanificacion;
use App\PlnProyectoRegion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PlanificacionRegion extends Controller
{    
    public function planificacionRegion(){
        $ultimoProyecto=PlnProyectoPlanificacion::where(array('estado'=>  HPMEConstants::ABIERTO))->first(['ide_proyecto','descripcion']);
        Log::info("ultimo ".$ultimoProyecto);
        if(!is_null($ultimoProyecto)){
            Log::info('No es null '.$ultimoProyecto);
            //$regionQuery=new PlnProyectoRegion();
            $regiones=  DB::select(HPMEConstants::PROYECTOS_REGION_QUERY,array('ideProyecto'=>$ultimoProyecto->ide_proyecto)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            Log::info("count ".count($regiones));
            Log::info($regiones);
            foreach ($regiones as $region){
                Log::info('proyecto region: '.$region->ide_proyecto_region);
            }
            if(count($regiones)>0){
                return view('planificacionregion',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion));
            }          
        }        
        return view('planificacionregion');
    }

    public function planificacionRegionDetalle($id){ 
        $proyectoRegion=  PlnProyectoRegion::find($id);
        if(!is_null($proyectoRegion)){
            Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
            Log::info($proyectoPlanificacion->descripcion);
            
//            $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$proyectoPlanificacion->ide_proyecto));
//            //$plantilla[]=array('metas'=>$metas);
//            foreach($metas as $meta){
//                $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
//                Log::info("## objetivos ".count($objetivos));
//                Log::info($objetivos);
//            }
//            
            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto);
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            
            Log::info($plantilla);
            return view('planificacion_region_detalle');
        }else{
            return view('home');
        }      
    } 

    private function obtenerMetas($ideProyecto){
        Log::info("### obtiniendo metas");
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        Log::info($metas);
        foreach($metas as $meta){
            $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            Log::info("## objetivos ".count($objetivos));
            Log::info($objetivos);
        }        
        return $metas;
    }
}
