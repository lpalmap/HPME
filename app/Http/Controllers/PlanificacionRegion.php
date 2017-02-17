<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\HPMEConstants;
use App\PlnProyectoPlanificacion;
use App\PlnProyectoRegion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\CfgRegion;
use App\PlnBitacoraProyectoRegion;
use App\PrivilegiosConstants;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\MonProyectoPeriodo;


class PlanificacionRegion extends Controller
{    
    public function planificacionRegion(){
        $ultimoProyecto=PlnProyectoPlanificacion::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto','descripcion','estado']);
        $rol=  request()->session()->get('rol');
        $consulta=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($ultimoProyecto) && ($apruebaPlanificacion || $consulta)){
            $puedeCerrar=$apruebaPlanificacion;
            $regiones=  DB::select(HPMEConstants::PROYECTOS_REGION_QUERY,array('ideProyecto'=>$ultimoProyecto->ide_proyecto)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            if(count($regiones)>0){
                return view('planificacionregion',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyecto'=>$ultimoProyecto->ide_proyecto,'estado'=>$ultimoProyecto->estado,'puedeCerrar'=>$puedeCerrar));
            }          
        }        
        return view('planificacionregion');
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
    
    private function ingresoPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFIACION_INGRESAR_PLANIFICACION, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    private function consultaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFICACION_CONSULTA_REGIONES, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    public function planificacionConsolidada($ideProyecto){
        $rol=  request()->session()->get('rol');
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($ideProyecto) && ($apruebaPlanificacion || $this->consultaPlanificacion())){
            $parametrosVista=  $this->parametrosConsolidado($ideProyecto);
            return view('planificacion_detalle_consolidado',$parametrosVista);
        }else{
            return view('home');
        } 
        
    }

    public function planificacionRegionDetalle($id){ 
        $proyectoRegion=  PlnProyectoRegion::find($id);
        $rol=  request()->session()->get('rol');
        $ingresaPlan=  $this->ingresoPlanificacion();
        $consultaPlanificacion=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($proyectoRegion) && ($apruebaPlanificacion || $ingresaPlan || $consultaPlanificacion)){
            $adminRegion=false;
            if($ingresaPlan && (!$consultaPlanificacion)){
                $ideRegion=$this->regionUsuario();
                if(is_null($ideRegion)){
                    return view('home');
                }else{
                    if($ideRegion!=$proyectoRegion->ide_region){
                        return view('home');
                    }else{
                        $adminRegion=true;
                    }
                }
            }

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
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>$adminRegion,'apruebaPlanificacion'=>$apruebaPlanificacion));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
        }else{
            return view('home');
        }      
    }
    
    public function aprobarPlanificacion(Request $request){
        $planificacion=  PlnProyectoRegion::find($request->ide_proyecto_region);
        if(!is_null($planificacion)){
            $count= PlnBitacoraProyectoRegion::where(array('ide_proyecto_region'=>$request->ide_proyecto_region,'estado'=>  HPMEConstants::ABIERTO))->count();
            if(!is_null($count) && $count>0){
                return response()->json(array('error'=>'La planificaci&oacute;n tiene observaciones pendentes, debe marcarlas como resueltas para aprobar.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($planificacion->estado==HPMEConstants::APROBADO){
                return response()->json(array('error'=>'Ya se encuentra aprobada la planificaci&oacute;n para la regi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($planificacion->estado==HPMEConstants::ABIERTO){
                return response()->json(array('error'=>'La planificaci&oacute;n se encuentra en estado '.HPMEConstants::ABIERTO.' no se ha enviado para su revisi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            date_default_timezone_set(HPMEConstants::TIME_ZONE);
            $planificacion->estado=  HPMEConstants::APROBADO;
            $planificacion->fecha_aprobacion=date(HPMEConstants::DATE_FORMAT,  time());
            $planificacion->save();
            $planificacion->region;
            try{
                $region=  CfgRegion::where('ide_region','=',$planificacion->ide_region)->first();
                $region->administradores();
                $proyecto = PlnProyectoPlanificacion::where('ide_proyecto','=',$planificacion->ide_proyecto_planificacion)->pluck('descripcion')->first();
                $this->enviarNotificacionAprobado($proyecto.'/'.$region['nombre'],$region->administradores[0]); 
            } catch(\Exception $e){
                //Si ocurre un error al enviar el correo se ignora y se agrega el mensaje en bitacora de todos modos
            } 
            return response()->json();
        }
    }
    
    private function enviarNotificacionAprobado($asunto,$usuario){
        $para=$usuario->email;
        $user=Auth::user();
        if(strlen($para)>0){   
            $mensaje='Felicidades!!! Su planificaci&oacute;n para el proyecto '.$asunto.' ha sido aprobada.';
            Mail::send('emails.reminder', ['title' => 'Aprobaci&oacute;n Planificaci&oacute;n', 'content' => $mensaje], function ($message) use ($user,$asunto,$para)
            {
                $message->from(env('MAIL_USERNAME'), $user->nombres.' '.$user->apellidos);
                $message->to(array($para));              
                $message->subject($asunto);

            });
        }     
    }

    public function planificacionProyectoDetalle($id){ 
        $proyectoPlanificacion = PlnProyectoPlanificacion::find($id);     
        $rol=  request()->session()->get('rol');
        $ingresaPlan=$this->ingresoPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($proyectoPlanificacion) && ($ingresaPlan)){
            $ideRegion=$this->regionUsuario();
            if(is_null($ideRegion)){
                return view('home');
            }  
            $ideProyectoRegion=PlnProyectoRegion::where(array("ide_region"=>$ideRegion,"ide_proyecto_planificacion"=>$id))->pluck('ide_proyecto_region')->first(); 
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            
            if(is_null($ideProyectoRegion)){
                $region=  CfgRegion::find($ideRegion);
                return view('planificacion_region_detalle',array('plantilla'=>array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> array()),'region'=>$region->nombre,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ingresaPlan'=>$ingresaPlan,'apruebaPlanificacion'=>$apruebaPlanificacion));
            }
            
            $proyectoRegion=  PlnProyectoRegion::find($ideProyectoRegion);
            if(is_null($proyectoRegion)){
                return view('home');
            }

            $proyectoRegion->region;
            $nombreRegion=$proyectoRegion->region->nombre;
        
            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$proyectoRegion->ide_proyecto_region);
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>$ingresaPlan,'apruebaPlanificacion'=>$apruebaPlanificacion));
        }else{
            return view('home');
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

    private function obtenerMetas($ideProyecto,$ideProyectoRegion=null){
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        //Log::info($metas);
        $result=array();
        foreach($metas as $meta){
            $objetivos=$this->obtenerObjetivos($meta->ide_proyecto_meta,$ideProyectoRegion);//DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            if(!empty($objetivos)){
                $result[]=array('meta'=>$meta,'objetivos'=>$objetivos);
            }
        }        
        return $result;
    }
    
    private function obtenerObjetivos($ideProyectoMeta,$ideProyectoRegion=null){
        //Log::info('Obteniendo objetivos');
        $objetivos=DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$ideProyectoMeta));
        $result=array();
        foreach($objetivos as $objetivo){
            $areas=$this->obtenerAreaAtencion($objetivo->ide_objetivo_meta,$ideProyectoRegion);
            if(!empty($areas)){
                $result[]=array('objetivo'=>$objetivo,'areas'=>$areas);
            } 
        }
        return $result;
    }
    
    private function obtenerAreaAtencion($ideObjetivoMeta,$ideProyectoRegion=null){
        //Log::info('Obteniendo areas');
        $areas=DB::select(HPMEConstants::PLN_AREAS_POR_OBJETIVO,array('ideObjetivoMeta'=>$ideObjetivoMeta));              
        $result=array();
        foreach ($areas as $area){
            $indicadores=$this->obtenerIndicadores($area->ide_area_objetivo,$area->orden_especial,$ideProyectoRegion);
            if(!empty($indicadores)){
                $result[]=array('area'=>$area,'indicadores'=>$indicadores);
            }   
        }
        return $result; 
    }
    
    private function obtenerIndicadores($ideAreaObjetivo,$ordenEspecial,$ideProyectoRegion=null){   
        $indicadores=DB::select(HPMEConstants::PLN_INDICADORES_POR_AREA,array('ideAreaObjetivo'=>$ideAreaObjetivo));
        $result=array();
        $ordenPorProducto=FALSE;
        if($ordenEspecial==='S'){
            $ordenPorProducto=TRUE;
        }
        foreach ($indicadores as $indicador){
            $productos=$this->obtenerProductos($indicador->ide_indicador_area,$ideProyectoRegion);
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
    
    
    
    
    private function obtenerProductos($ideIndicadorArea,$ideProyectoRegion=null){
        $productos=DB::select(HPMEConstants::PLN_PRODUCTOS_POR_INDICADOR,array('ideIndicadorArea'=>$ideIndicadorArea));    
        Log::info("### productos $ideIndicadorArea");
        $result=array();
        foreach($productos as $producto){
            $detalle=$this->obtenerDetalleProductoRegion($producto->ide_producto_indicador, $ideProyectoRegion);
            if(!empty($detalle)){
                $result[]=array('producto'=>$producto,'detalles'=>$detalle);
            }          
        }
        return $result;        
    
        
    }
    
    private function obtenerDetalleProductoRegion($ideProductoIndicador,$ideProyectoRegion=null){
        if(is_null($ideProyectoRegion)){
            return DB::select(HPMEConstants::PLN_CONSOLIDADO_POR_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador));
        }else{
            $detalleProducto=DB::select(HPMEConstants::PLN_REGION_PRODUCTO,array('ideProductoIndicador'=>$ideProductoIndicador,'ideProyectoRegion'=>$ideProyectoRegion));
            $result=array();
            foreach($detalleProducto as $detalle){
                $items=$this->obtenerValoresItems($detalle->ide_region_producto);
                $result[]=array('detalle'=>$detalle,'valores'=>$items);
            }
            return $result;
        }       
    }
    
    private function obtenerValoresItems($ideRegionProducto){
        $valores=DB::select(HPMEConstants::PLN_DETALLE_POR_PRODUCTO_REGION,array('ideRegionProducto'=>$ideRegionProducto));
        return $valores;
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
    
    public function monitoreoAfiliadoDetalle2($idePeriodoMonitoreo){
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
            $encabezados[]='Enero-Marzo';
            return view('monitoreo_afiliado_detalle2',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>FALSE)); 
    }
    
    public function exportarPlanificacionRegion($id){ 
        $proyectoRegion=  PlnProyectoRegion::find($id);
        $rol=  request()->session()->get('rol');
        $ingresaPlan=  $this->ingresoPlanificacion();
        $consultaPlanificacion=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($proyectoRegion) && ($apruebaPlanificacion || $ingresaPlan || $consultaPlanificacion)){   
            if($ingresaPlan && !$consultaPlanificacion){
                $ideRegion=$this->regionUsuario();
                if(is_null($ideRegion)){
                    return view('home');
                }else{
                    if($ideRegion!=$proyectoRegion->ide_region){
                        return view('home');
                    }
                }
            }
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
            $proyectoRegion->region;
            $nombreRegion=$proyectoRegion->region->nombre;

            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto,$proyectoRegion->ide_proyecto_region);
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            
            $encabezados=array();
            $encabezados[]='Enero-Marzo';
            $encabezados[]='Abril-Junio';
            $encabezados[]='Julio-Septiembre';
            $encabezados[]='Octubre-Diciembre';
            
            Excel::create("Planificación $nombreRegion", function($excel) use($plantilla,$encabezados) {
            $excel->sheet('Planificado', function($sheet) use ($plantilla,$encabezados){
                $sheet->loadView('planificacion_region_export', array('plantilla'=>$plantilla,'num_items'=>count($encabezados),'encabezados'=>$encabezados));
                $sheet->freezeFirstRow();
            });
            })->export('xls');
        }else{
            return view('home');
        }      
    }
    
    public function exportPlanificacionConsolidada($ideProyecto){
        $rol=  request()->session()->get('rol');
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($ideProyecto) && ($apruebaPlanificacion || $this->consultaPlanificacion())){
            $parametrosVista=  $this->parametrosConsolidado($ideProyecto);
            $nombreArchivo='Consolidado '.$parametrosVista['plantilla']['proyecto'];
            
            Excel::create($nombreArchivo, function($excel) use($parametrosVista) {
                $excel->sheet('Planificado', function($sheet) use ($parametrosVista){
                    $sheet->loadView('planificacion_consolidado_export', $parametrosVista);
                    $sheet->setFitToWidth();
                    $sheet->freezeFirstRow();
                });
            })->export('xls');
        }else{
            return view('home');
        }      
    }
    //Construye la vista para la planificacion consolidada
    private function parametrosConsolidado($ideProyecto){
        $proyectoPlanificacion = PlnProyectoPlanificacion::find($ideProyecto);  
        $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto);         
        $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
        $encabezados=array();
        $encabezados[]='Ene-Mar';
        $encabezados[]='Abr-Jun';
        $encabezados[]='Jul-Sep';
        $encabezados[]='Oct-Dic';
        return array('plantilla'=>$plantilla,'ideProyecto'=>$ideProyecto,'num_items'=>count($encabezados),'encabezados'=>$encabezados);
    }
    
    private function plantillasExport($ideProyecto){
        $regiones=  PlnProyectoRegion::where('ide_proyecto_planificacion',$ideProyecto)->pluck('ide_proyecto_region');
        $planes=array();
        foreach($regiones as $region){
            $proyectoRegion= PlnProyectoRegion::find($region);
            $nombreRegion=  CfgRegion::where('ide_region',$proyectoRegion->ide_region)->pluck('nombre')->first();
            $metas=$this->obtenerMetas($proyectoRegion->ide_proyecto_planificacion,$proyectoRegion->ide_proyecto_region);
            $planes[]=array("region"=>$nombreRegion,'metas'=> $metas);
        }    
        return $planes;        
    }

    public function planificacionExport($ideProyecto){
        $rol=  request()->session()->get('rol');
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($ideProyecto) && ($apruebaPlanificacion || $this->consultaPlanificacion())){
            $parametrosVista=  $this->parametrosConsolidado($ideProyecto);
            $nombreArchivo='Consolidado '.$parametrosVista['plantilla']['proyecto'];
            Log::info('Test');
            $regiones=$this->plantillasExport($ideProyecto);
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            $num_items=count($encabezados);
            set_time_limit(0);
            Excel::create($nombreArchivo, function($excel) use($parametrosVista,$regiones,$num_items,$encabezados) {
                $excel->sheet('Consolidado', function($sheet) use ($parametrosVista){
                    $sheet->loadView('planificacion_consolidado_export', $parametrosVista);
                    $sheet->freezeFirstRow();
                });
                Excel::shareView('planificacion_region_export')->create();
                foreach ($regiones as $region){
                    $excel->sheet($region['region'], function($sheet) use ($region,$num_items,$encabezados){
                        $sheet->loadView('planificacion_region_export', array('plantilla'=>$region,'num_items'=>$num_items,'encabezados'=>$encabezados));
                        $sheet->freezeFirstRow();
                    });
                }
            })->export('xls');
        }else{
            return view('home');
        }  
    }
    
}
