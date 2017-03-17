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
use App\MonPeriodoRegion;
use App\CfgRegion;
use App\PlnProyectoRegion;
use App\PlnRegionProductoDetalle;
use App\MonArchivoProductoPeriodo;
use App\MonBitacoraPeriodo;

class MonitoreoRegion extends Controller
{
    public function periodoRegion($idePeriodoRegion){
        $vistaPrivilegio=$this->vistaPrivilegio();
        $periodoRegion=  MonPeriodoRegion::find($idePeriodoRegion);
        $region=PlnProyectoRegion::where('ide_proyecto_region','=',$periodoRegion->ide_proyecto_region)->pluck('ide_region')->first();
        $ingresaMon=$this->ingresoMonitoreo();
        if(!$vistaPrivilegio){          
            if($ingresaMon){
                $regionUsuario=  $this->regionUsuario();
                if($region!==$regionUsuario){
                    return view('home');
                }
            }else{
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
        $apruebaPlanificaion=$this->apruebaPlanificacion();
        $encabezados=array();
        $encabezados[]=  MonProyectoPeriodo::where('ide_periodo_monitoreo','=',$periodoRegion->ide_periodo_monitoreo)->pluck('descripcion')->first();
        return view('monitoreo_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$periodoRegion->estado,'vistaPrivilegio'=>$vistaPrivilegio,'periodo'=>$periodo,'idePeriodoRegion'=>$idePeriodoRegion,'ideProyectoPlanificacion'=>$proyectoRegion->ide_proyecto_planificacion,'ingresaMon'=>$ingresaMon,'apruebaPlanificacion'=>$apruebaPlanificaion)); 
    }
    
    private function apruebaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFICACION_APROBAR_PLANIFICACION, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    public function aprobarPeriodoRegion(Request $request){
        $periodo= MonPeriodoRegion::find($request->ide_periodo_region);
        if(!is_null($periodo)){
            $count= MonBitacoraPeriodo::where(array('ide_periodo_region'=>$periodo->ide_periodo_region,'estado'=>  HPMEConstants::ABIERTO))->count();
            if(!is_null($count) && $count>0){
                return response()->json(array('error'=>'La ejecuci&oacute;n tiene observaciones pendentes, debe marcarlas como resueltas para aprobar.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($periodo->estado==HPMEConstants::APROBADO){
                return response()->json(array('error'=>'Ya se encuentra aprobada la ejecucioni&oacute;n del periodo para la regi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($periodo->estado==HPMEConstants::ABIERTO){
                return response()->json(array('error'=>'La ejecuci&oacute;n se encuentra en estado '.HPMEConstants::ABIERTO.' no se ha enviado para su revisi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            date_default_timezone_set(HPMEConstants::TIME_ZONE);
            $periodo->estado=  HPMEConstants::APROBADO;
            $periodo->fecha_aprobacion=date(HPMEConstants::DATE_FORMAT,  time());
            $periodo->save();
            $proyectoRegion=  PlnProyectoRegion::find($periodo->ide_proyecto_region);
            try{
                $region=CfgRegion::where('ide_region','=',$proyectoRegion->ide_region)->first();
                $region->administradores();
                $proyecto = PlnProyectoPlanificacion::where('ide_proyecto','=',$planificacion->ide_proyecto_planificacion)->pluck('descripcion')->first();
                $this->enviarNotificacionAprobado($proyecto.'/'.$region['nombre'],$region->administradores[0]); 
            } catch(\Exception $e){
                //Si ocurre un error al enviar el correo se ignora y se agrega el mensaje en bitacora de todos modos
            } 
            return response()->json();
        }else{
            response()->json("No se encontro el periodo ".$request->ide_periodo_region);
        }
    }
    
    public function enviarPeriodoRegion(Request $request){
        $region=  MonPeriodoRegion::find($request->ide_periodo_region);
        if($region->estado!==HPMEConstants::ABIERTO){
            return response()->json(array('error'=>'Solo se pueden enviar periodos en estado '.HPMEConstants::ABIERTO), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $region->estado=  HPMEConstants::ENVIADO;
        $region->save();
        return response()->json();
    }
    
    private function enviarNotificacionAprobado($asunto,$usuario){
        $para=$usuario->email;
        $user=Auth::user();
        if(strlen($para)>0){   
            $mensaje='Felicidades!!! Su ejecuci&oacute;n para el proyecto '.$asunto.' ha sido aprobada.';
            Mail::send('emails.reminder', ['title' => 'Aprobaci&oacute;n Ejecuci&oacute;n', 'content' => $mensaje], function ($message) use ($user,$asunto,$para)
            {
                $message->from(env('MAIL_USERNAME'), $user->nombres.' '.$user->apellidos);
                $message->to(array($para));              
                $message->subject($asunto);
            });
        }     
    }
    
//    public function monitoreoAfiliadoDetalle($idePeriodoMonitoreo){
//        $monitoreo=  MonProyectoPeriodo::find($idePeriodoMonitoreo);
//        $ideRegion=$this->regionUsuario();
//        $id=7;
//        $proyectouser=  PlnProyectoRegion::where('ide_region',$ideRegion)->where('ide_proyecto_planificacion',$monitoreo->ide_proyecto)->pluck('ide_proyecto_region')->first();
//        $id=$proyectouser;
//                
//        
//        $proyectoRegion=  PlnProyectoRegion::find($id);
//        $rol=  request()->session()->get('rol');
//        
//        $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
//            $proyectoRegion->region;
//            $nombreRegion=$proyectoRegion->region->nombre;
//
//            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$proyectoRegion->ide_proyecto_region);
//            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
//            
//            $encabezados=array();
//            $encabezados[]='Ene-Mar';
//            $encabezados[]='Abr-Jun';
//            $encabezados[]='Jul-Sep';
//            $encabezados[]='Oct-Dic';
//            return view('monitoreo_afiliado_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>FALSE)); 
//    }
    
    public function guardarDetalleProducto(Request $request){
        $detalle=  PlnRegionProductoDetalle::find($request->ide_region_producto_detalle);
        $ejecutado=$request->ejecutado;
        if($ejecutado<0){
            return response()->json(array('error'=>'Debe ingresar un valor ejecutado.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        if($request->requiere_archivo===HPMEConstants::SI){
            $archivos= MonArchivoProductoPeriodo::where('ide_region_producto_detalle','=',$detalle->ide_region_producto_detalle)->count();
            if($archivos===0){
                return response()->json(array('error'=>'El producto requiere cargar archivos para comprobar la ejecuci&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
        }
        $detalle->ejecutado=$ejecutado;
        $detalle->save();
        return response()->json();
    }
    
    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::MONITOREO_ADMINISTRACION, $privilegios)
                    || in_array(PrivilegiosConstants::PLANIFICACION_APROBAR_PLANIFICACION, $privilegios)
                            || in_array(PrivilegiosConstants::PLANIFICACION_CONSULTA_REGIONES, $privilegios)
                                    || in_array(PrivilegiosConstants::PLANIFICACION_CREAR_PROYECTO,$privilegios)
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
    
//    public function detalleProducto($ideRegionProducto,$periodo){
//       Log::info("Producto: $ideRegionProducto periodo $periodo");
//       $detalle=PlnRegionProductoDetalle::where(array('ide_region_producto'=>$ideRegionProducto,'num_detalle'=>$periodo))->first();
//       return response()->json($detalle);
//    }
    
    public function detalleProducto($ideRegionProductoDetalle){
       //Log::info("Producto: $ideRegionProducto periodo $periodo");
       //Log::info("Region producto detalle $ideRegionProductoDetalle");
       //$detalle=PlnRegionProductoDetalle::where(array('ide_region_producto'=>$ideRegionProducto,'num_detalle'=>$periodo))->first();
       $detalle=  PlnRegionProductoDetalle::find($ideRegionProductoDetalle);//where(array('ide_region_producto_detalle'=>$ideRegionProductoDetalle))->first();   
       $detalle->archivos;
       return response()->json($detalle);     
    }
}