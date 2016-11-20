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
        //Log::info("ultimo ".$ultimoProyecto);
        if(!is_null($ultimoProyecto)){
            //Log::info('No es null '.$ultimoProyecto);
            //$regionQuery=new PlnProyectoRegion();
            $regiones=  DB::select(HPMEConstants::PROYECTOS_REGION_QUERY,array('ideProyecto'=>$ultimoProyecto->ide_proyecto)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            //Log::info("count ".count($regiones));
            //Log::info($regiones);
//            foreach ($regiones as $region){
//                Log::info('proyecto region: '.$region->ide_proyecto_region);
//            }
            if(count($regiones)>0){
                return view('planificacionregion',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyecto'=>$ultimoProyecto->ide_proyecto));
            }          
        }        
        return view('planificacionregion');
    }
    
    public function planificacionConsolidada($ideProyecto){
        if(!is_null($ideProyecto)){
            //Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($ideProyecto);  
            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto);         
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            return view('planificacion_detalle_consolidado',array('plantilla'=>$plantilla,'num_items'=>count($encabezados),'encabezados'=>$encabezados));
        }else{
            return view('home');
        } 
        
    }

    public function planificacionRegionDetalle($id){ 
        $proyectoRegion=  PlnProyectoRegion::find($id);
        if(!is_null($proyectoRegion)){
            //Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
            //Log::info($proyectoPlanificacion->descripcion);
            $proyectoRegion->region;
            $nombreRegion=$proyectoRegion->region->nombre;
//            $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$proyectoPlanificacion->ide_proyecto));
//            //$plantilla[]=array('metas'=>$metas);
//            foreach($metas as $meta){
//                $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
//                Log::info("## objetivos ".count($objetivos));
//                Log::info($objetivos);
//            }
//            
            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$proyectoRegion->ide_proyecto_region);
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            
//            Log::info($plantilla);
//            Log::info('#### recorriendo plantilla');
//            Log::info($plantilla['metas']);
//            for($i=0;$i<count($plantilla['metas']);$i++){
//                Log::info('##### metas '.$i);
//                Log::info($plantilla['metas'][$i]['meta']->nombre);
//                Log::info('##### objetivos');
//                //Log::info($plantilla['metas'][$i]['objetivos']);
//                for($o=0;$o<count($plantilla['metas'][$i]['objetivos']);$o++){
//                    Log::info("###### nuevo objetivo");
//                    Log::info($plantilla['metas'][$i]['objetivos'][$o]['objetivo']->nombre);
//                    //Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas']);
//                    for($a=0;$a<count($plantilla['metas'][$i]['objetivos'][$o]['areas']);$a++){
//                        Log::info("Areas... ");
//                        Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['area']->nombre);
//                        for($in=0;$in<count($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores']);$in++){
//                            Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['indicador']->nombre);
//                            for($p=0;$p<count($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos']);$p++){
//                                Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['producto']->nombre);
//                                //Log::info('#### ide_producto_indicaodr'.$plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['producto']->ide_producto_indicador);
//                                //$this->printt($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['detalles']);
//                                
//                               // Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['detalles']);
//                                for($d=0;$d<count($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['detalles']);$d++){
//                                    Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['detalles'][$d]['detalle']->proyecto);
//                                    Log::info($plantilla['metas'][$i]['objetivos'][$o]['areas'][$a]['indicadores'][$in]['productos'][$p]['detalles'][$d]['valores']);
//                                }
//                            }
//                        }
//                        
//                    }        
//                }
//                break;
//            }
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
        }else{
            return view('home');
        }      
    } 
    
    private function printt($detalles){
        //Log::info('### printllll '.count($detalles));
        for($d=0;count($detalles);$d++){
          //  Log::info($detalles[$d]['detalle']->proyecto);
            //Log::info("FINIIIIIIIII ");
        }
    }

    private function obtenerMetas($ideProyecto,$ideProyectoRegion=null){
        //Log::info("### obtiniendo metas");
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        //Log::info($metas);
        $result=array();
        foreach($metas as $meta){
            $objetivos=$this->obtenerObjetivos($meta->ide_proyecto_meta,$ideProyectoRegion);//DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            //Log::info("## objetivos ".count($objetivos));
            //Log::info($objetivos);
            $result[]=array('meta'=>$meta,'objetivos'=>$objetivos);
        }        
        return $result;
    }
    
    private function obtenerObjetivos($ideProyectoMeta,$ideProyectoRegion=null){
        //Log::info('Obteniendo objetivos');
        $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$ideProyectoMeta));
        $result=array();
        foreach($objetivos as $objetivo){
            $areas=$this->obtenerAreaAtencion($objetivo->ide_objetivo_meta,$ideProyectoRegion);
            $result[]=array('objetivo'=>$objetivo,'areas'=>$areas);
        }
        return $result;
    }
    
    private function obtenerAreaAtencion($ideObjetivoMeta,$ideProyectoRegion=null){
        //Log::info('Obteniendo areas');
        $areas=DB::select(HPMEConstants::PLN_AREAS_POR_OBJETIVO,array('ideObjetivoMeta'=>$ideObjetivoMeta));              
        $result=array();
        foreach ($areas as $area){
            $indicadores=$this->obtenerIndicadores($area->ide_area_objetivo,$ideProyectoRegion);
            $result[]=array('area'=>$area,'indicadores'=>$indicadores);
        }
        return $result; 
    }
    
    private function obtenerIndicadores($ideAreaObjetivo,$ideProyectoRegion=null){
        //Log::info('Obteniendo indicadores...');
        $indicadores=DB::select(HPMEConstants::PLN_INDICADORES_POR_AREA,array('ideAreaObjetivo'=>$ideAreaObjetivo));
        $result=array();
        foreach ($indicadores as $indicador){
            $productos=$this->obtenerProductos($indicador->ide_indicador_area,$ideProyectoRegion);
            $result[]=array('indicador'=>$indicador,'productos'=>$productos);
        }
        return $result;     
    }
    
    private function obtenerProductos($ideIndicadorArea,$ideProyectoRegion=null){
        //Log::info('Obteniendo productos...');
        $productos=DB::select(HPMEConstants::PLN_PRODUCTOS_POR_INDICADOR,array('ideIndicadorArea'=>$ideIndicadorArea));    
        $result=array();
        foreach($productos as $producto){
            $detalle=$this->obtenerDetalleProductoRegion($producto->ide_producto_indicador, $ideProyectoRegion);
            //Log::info('##### detalles '.count($detalle));
            $result[]=array('producto'=>$producto,'detalles'=>$detalle);
        }
        return $result;        
    
        
    }
    
    private function obtenerDetalleProductoRegion($ideProductoIndicador,$ideProyectoRegion=null){
        //Log::info('Obteniendo detalles,,,,,');
        if(is_null($ideProyectoRegion)){
            return DB::select(HPMEConstants::PLN_CONSOLIDADO_POR_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador));
        }else{
            //$detalleProducto=DB::select(HPMEConstants::PLN_REGION_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));             
            //Log::info('Obteniendo detalle###### '.$ideProyectoRegion.' proyecto '.$ideProductoIndicador);
            $detalleProducto=DB::select(HPMEConstants::PLN_REGION_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));
            $result=array();
            //Log::info('Detalles....  '.count($detalleProducto));
            foreach($detalleProducto as $detalle){
                $items=$this->obtenerValoresItems($detalle->ide_region_producto);
                $result[]=array('detalle'=>$detalle,'valores'=>$items);
            }
            return $result;
        }       
    }
    
    private function obtenerValoresItems($ideRegionProducto){
        //Log::info('Obteniendo detalle items. '.$ideRegionProducto);
        $valores=DB::select(HPMEConstants::PLN_DETALLE_POR_PRODUCTO_REGION,array('ideRegionProducto'=>$ideRegionProducto));
        //Log::info('Count valores.... '.count($valores));
        return $valores;
    }
    
}
