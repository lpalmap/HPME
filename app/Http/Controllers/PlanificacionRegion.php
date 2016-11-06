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

    private function obtenerMetas($ideProyecto,$ideProyectoRegion=null){
        Log::info("### obtiniendo metas");
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        Log::info($metas);
        $result=array();
        foreach($metas as $meta){
            $objetivos=$this->obtenerObjetivos($meta->ide_proyecto_meta);//DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            //Log::info("## objetivos ".count($objetivos));
            //Log::info($objetivos);
            $result[]=array('meta'=>$meta,'objetivos'=>$objetivos);
        }        
        return $result;
    }
    
    private function obtenerObjetivos($ideProyectoMeta,$ideProyectoRegion=null){
        Log::info('Obteniendo objetivos');
        $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$ideProyectoMeta));
        $result=array();
        foreach($objetivos as $objetivo){
            $areas=$this->obtenerAreaAtencion($objetivo->ide_objetivo_meta);
            $result[]=array('objetivo'=>$objetivo,'areas'=>$areas);
        }
        return $result;
    }
    
    private function obtenerAreaAtencion($ideObjetivoMeta,$ideProyectoRegion=null){
        Log::info('Obteniendo areas');
        $areas=DB::select(HPMEConstants::PLN_AREAS_POR_OBJETIVO,array('ideObjetivoMeta'=>$ideObjetivoMeta));              
        $result=array();
        foreach ($areas as $area){
            $indicadores=$this->obtenerIndicadores($area->ide_area_objetivo);
            $result[]=array('area'=>$area,'indicadores'=>$indicadores);
        }
        return $result; 
    }
    
    private function obtenerIndicadores($ideAreaObjetivo,$ideProyectoRegion=null){
        Log::info('Obteniendo indicadores...');
        $indicadores=DB::select(HPMEConstants::PLN_INDICADORES_POR_AREA,array('ideAreaObjetivo'=>$ideAreaObjetivo));
        $result=array();
        foreach ($indicadores as $indicador){
            $productos=$this->obtenerProductos($indicador->ide_indicador_area);
            $result[]=array('indicador'=>$indicador,'productos'=>$productos);
        }
        return $result;     
    }
    
    private function obtenerProductos($ideIndicadorArea,$ideProyectoRegion=null){
        Log::info('Obteniendo productos...');
        $productos=DB::select(HPMEConstants::PLN_PRODUCTOS_POR_INDICADOR,array('ideIndicadorArea'=>$ideIndicadorArea));;    
        $result=array();
        foreach($productos as $producto){
            $detalle=$this->obtenerDetalleProductoRegion($producto->ide_producto_indicador, $ideProyectoRegion);
            $result[]=array('producto'=>$producto,'detalles'=>$detalle);
        }
        return $productos;        
    
        
    }
    
    private function obtenerDetalleProductoRegion($ideProductoIndicador,$ideProyectoRegion=null){
        if(is_null($ideProyectoRegion)){
            //Detalle consolidado
            return array('consolidado'=>1);
        }else{
            $detalleProducto=DB::select(HPMEConstants::PLN_REGION_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));
                
            $detalleProducto=DB::select(HPMEConstants::PLN_DETALLE_POR_PRODUCTO_REGION,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));
            
            return $detalleProducto;
        }       
    }
    
}
