<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\PlnProyectoPlanificacion;
use App\HPMEConstants;
use App\MonProyectoPeriodo;
use App\PrivilegiosConstants;
use App\CfgListaValor;
use App\MonPeriodoRegion;
use App\CfgRegion;
use App\PlnProyectoRegion;
use App\PlnRegionProductoDetalle;

class MonitoreoRegion extends Controller
{
    public function periodoRegion($idePeriodoRegion){
        $vistaPrivilegio=$this->vistaPrivilegio();
        $periodoRegion=  MonPeriodoRegion::find($idePeriodoRegion);
        $region=PlnProyectoRegion::where('ide_proyecto_region','=',$periodoRegion->ide_proyecto_region)->pluck('ide_region')->first();
        Log::info("Region proyecto $region");
        if(!$vistaPrivilegio){
            $ingresaMon=$this->ingresoMonitoreo();
            if($ingresaMon){
                $regionUsuario=  $this->regionUsuario();
                Log::info("Region usuario: $regionUsuario");
                if($region!==$regionUsuario){
                    Log::info("flasdjflkasdf region no valida");
                    return view('home');
                }
            }else{
                 Log::info("no ingresa mon");
                return view('home');
            }          
        }
        $periodo=  MonProyectoPeriodo::where('ide_periodo_monitoreo','=',$periodoRegion->ide_periodo_monitoreo)->pluck('no_periodo')->first();
        $proyectoRegion=  PlnProyectoRegion::find($periodoRegion->ide_proyecto_region);
        $rol=  request()->session()->get('rol');
        
        $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
        $proyectoRegion->region;
        $nombreRegion=$proyectoRegion->region->nombre;

        $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$periodo,$periodoRegion->ide_proyecto_region);
        $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);

        $encabezados=array();
        $encabezados[]=  MonProyectoPeriodo::where('ide_periodo_monitoreo','=',$periodoRegion->ide_periodo_monitoreo)->pluck('descripcion')->first();
        return view('monitoreo_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$periodoRegion->estado,'ingresaPlan'=>FALSE,'periodo'=>$periodo)); 
    }
    
    
    public function monitoreoAfiliadoDetalle($idePeriodoMonitoreo){
        $monitoreo=  MonProyectoPeriodo::find($idePeriodoMonitoreo);
        $ideRegion=$this->regionUsuario();
        $id=7;
        $proyectouser=  PlnProyectoRegion::where('ide_region',$ideRegion)->where('ide_proyecto_planificacion',$monitoreo->ide_proyecto)->pluck('ide_proyecto_region')->first();
        $id=$proyectouser;
                
        
        $proyectoRegion=  PlnProyectoRegion::find($id);
        $rol=  request()->session()->get('rol');
        
        $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
            $proyectoRegion->region;
            $nombreRegion=$proyectoRegion->region->nombre;

            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$proyectoRegion->ide_proyecto_region);
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            return view('monitoreo_afiliado_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>FALSE)); 
    }
    
    
    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::MONITOREO_ADMINISTRACION, $privilegios)
                    || in_array(PrivilegiosConstants::PLANIFICACION_APROBAR_PLANIFICACION, $privilegios)
                            || in_array(PrivilegiosConstants::PLANIFICACION_CONSULTA_REGIONES, $privilegios)
                    ){
                return TRUE;
            }
        }  
        return FALSE;
    }
    
    private function ingresoMonitoreo(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFIACION_INGRESAR_PLANIFICACION,$privilegios)){
                return TRUE;
            }
        }  
        return FALSE;
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
    
    private function obtenerMetas($ideProyecto,$periodo,$ideProyectoRegion=null){
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        //Log::info($metas);
        $result=array();
        foreach($metas as $meta){
            $objetivos=$this->obtenerObjetivos($meta->ide_proyecto_meta,$periodo,$ideProyectoRegion);//DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            if(!empty($objetivos)){
                $result[]=array('meta'=>$meta,'objetivos'=>$objetivos);
            }
        }        
        return $result;
    }
    
    private function obtenerObjetivos($ideProyectoMeta,$periodo,$ideProyectoRegion=null){
        //Log::info('Obteniendo objetivos');
        $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$ideProyectoMeta));
        $result=array();
        foreach($objetivos as $objetivo){
            $areas=$this->obtenerAreaAtencion($objetivo->ide_objetivo_meta,$periodo,$ideProyectoRegion);
            if(!empty($areas)){
                $result[]=array('objetivo'=>$objetivo,'areas'=>$areas);
            } 
        }
        return $result;
    }
    
    private function obtenerAreaAtencion($ideObjetivoMeta,$periodo,$ideProyectoRegion=null){
        //Log::info('Obteniendo areas');
        $areas=DB::select(HPMEConstants::PLN_AREAS_POR_OBJETIVO,array('ideObjetivoMeta'=>$ideObjetivoMeta));              
        $result=array();
        foreach ($areas as $area){
            $indicadores=$this->obtenerIndicadores($area->ide_area_objetivo,$area->orden_especial,$periodo,$ideProyectoRegion);
            if(!empty($indicadores)){
                $result[]=array('area'=>$area,'indicadores'=>$indicadores);
            }   
        }
        return $result; 
    }
    
    private function obtenerIndicadores($ideAreaObjetivo,$ordenEspecial,$periodo,$ideProyectoRegion=null){   
        $indicadores=DB::select(HPMEConstants::PLN_INDICADORES_POR_AREA,array('ideAreaObjetivo'=>$ideAreaObjetivo));
        $result=array();
        $ordenPorProducto=FALSE;
        if($ordenEspecial==='S'){
            $ordenPorProducto=TRUE;
        }
        foreach ($indicadores as $indicador){
            $productos=$this->obtenerProductos($indicador->ide_indicador_area,$periodo,$ideProyectoRegion);
            if(!empty($productos)){
                if($ordenPorProducto){
                    foreach ($productos as $producto) {
                        //Log::info($producto);
                        $result[]=array('indicador'=>$indicador,'productos'=>array($producto),'orden'=>$producto['producto']->orden);
                    }
                    //Log::info($result);
                }else{
                    $result[]=array('indicador'=>$indicador,'productos'=>$productos);
                } 
            }
        }
        if($ordenPorProducto){
            usort($result, function($a, $b) {
                return $a['orden'] - $b['orden'];
            });
        }        
        return $result;     
    }
    
    
    
    
    private function obtenerProductos($ideIndicadorArea,$periodo,$ideProyectoRegion=null){
        $productos=DB::select(HPMEConstants::PLN_PRODUCTOS_POR_INDICADOR,array('ideIndicadorArea'=>$ideIndicadorArea));    
        $result=array();
        foreach($productos as $producto){
            $detalle=$this->obtenerDetalleProductoRegion($producto->ide_producto_indicador,$periodo, $ideProyectoRegion);
            if(!empty($detalle)){
                $result[]=array('producto'=>$producto,'detalles'=>$detalle);
            }          
        }
        return $result;        
    }
    
    private function obtenerDetalleProductoRegion($ideProductoIndicador,$periodo,$ideProyectoRegion=null){
        if(is_null($ideProyectoRegion)){
            return DB::select(HPMEConstants::PLN_CONSOLIDADO_POR_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador));
        }else{
            $detalleProducto=DB::select(HPMEConstants::PLN_REGION_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));
            $result=array();
            foreach($detalleProducto as $detalle){
                $items=$this->obtenerValoresItems($detalle->ide_region_producto,$periodo);
                $result[]=array('detalle'=>$detalle,'valores'=>$items);
            }
            return $result;
        }       
    }
    
    private function obtenerValoresItems($ideRegionProducto,$periodo){
        $valores=null;
        if($periodo===HPMEConstants::FILTRO_TODO){
            $valores=DB::select(HPMEConstants::PLN_DETALLE_POR_PRODUCTO_REGION,array('ideRegionProducto'=>$ideRegionProducto));
        }else{
            $valores=DB::select(HPMEConstants::PLN_DETALLE_POR_PRODUCTO_REGION_PERIODO,array('ideRegionProducto'=>$ideRegionProducto,'periodo'=>$periodo));
        }        
        return $valores;
    }
    
    public function detalleProducto($ideRegionProducto,$periodo){
       Log::info("Producto: $ideRegionProducto periodo $periodo");
       $detalle=PlnRegionProductoDetalle::where(array('ide_region_producto'=>$ideRegionProducto,'num_detalle'=>$periodo))->first();
       return response()->json($detalle);
    }
}