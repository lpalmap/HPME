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
use App\SegUsuario;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\MonProyectoPeriodo;


class PlanificacionRegion extends Controller
{    
    public function planificacionRegion(){
        $ultimoProyecto=PlnProyectoPlanificacion::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto','descripcion','estado']);
        //Log::info("ultimo ".$ultimoProyecto);
        $rol=  request()->session()->get('rol');
        $consulta=$this->consultaPlanificacion();
        if(!is_null($ultimoProyecto) && $rol=='COORDINADOR' || $consulta){
            //Log::info('No es null '.$ultimoProyecto);
            //$regionQuery=new PlnProyectoRegion();
            $puedeCerrar=$this->puedeCerrar();
            $regiones=  DB::select(HPMEConstants::PROYECTOS_REGION_QUERY,array('ideProyecto'=>$ultimoProyecto->ide_proyecto)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            //Log::info("count ".count($regiones));
            //Log::info($regiones);
//            foreach ($regiones as $region){
//                Log::info('proyecto region: '.$region->ide_proyecto_region);
//            }
            if(count($regiones)>0){
                return view('planificacionregion',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyecto'=>$ultimoProyecto->ide_proyecto,'estado'=>$ultimoProyecto->estado,'puedeCerrar'=>$puedeCerrar));
            }          
        }        
        return view('planificacionregion');
    }
    
    public function exportarPlanificacion(){
        Excel::create('Laravel Excel', function($excel) {
 
            $excel->sheet('Productos', function($sheet) {
 
                $products = PlnProyectoRegion::all();
 
                $sheet->fromArray($products);
 
            });
        })->export('xls');     
    }
    
    private function puedeCerrar(){
        $rol=  request()->session()->get('rol');
        if($rol=='COORDINADOR'){
            return TRUE;
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
        if(!is_null($ideProyecto) && ($rol=='COORDINADOR' || $this->consultaPlanificacion())){
            //Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($ideProyecto);  
            $metas=$this->obtenerMetas($proyectoPlanificacion->ide_proyecto);         
            $plantilla=array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> $metas);
            $encabezados=array();
            $encabezados[]='Ene-Mar';
            $encabezados[]='Abr-Jun';
            $encabezados[]='Jul-Sep';
            $encabezados[]='Oct-Dic';
            return view('planificacion_detalle_consolidado',array('plantilla'=>$plantilla,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol));
        }else{
            return view('home');
        } 
        
    }

    public function planificacionRegionDetalle($id){ 
        $proyectoRegion=  PlnProyectoRegion::find($id);
        $rol=  request()->session()->get('rol');
        $ingresaPlan=  $this->ingresoPlanificacion();
        $consultaPlanificacion=$this->consultaPlanificacion();
        if(!is_null($proyectoRegion) && ($rol=='COORDINADOR' || $rol == 'AFILIADO' || $ingresaPlan || $consultaPlanificacion)){   
            if($rol=='AFILIADO' || ($ingresaPlan && (!$consultaPlanificacion))){
                $ideRegion=$this->regionUsuario();
                if(is_null($ideRegion)){
                    return view('home');
                }else{
                    if($ideRegion!=$proyectoRegion->ide_region){
                        return view('home');
                    }
                }
            }
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
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>FALSE));
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
        if(!is_null($proyectoPlanificacion) && ($rol=='AFILIADO' || $ingresaPlan)){
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
                return view('planificacion_region_detalle',array('plantilla'=>array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> array()),'region'=>$region->nombre,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ingresaPlan'=>$ingresaPlan));
            }
            
            $proyectoRegion=  PlnProyectoRegion::find($ideProyectoRegion);
            if(is_null($proyectoRegion)){
                return view('home');
            }
            
            //Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            //$proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
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
            
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>$ingresaPlan));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
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
        //Log::info("### obtiniendo metas");
        $metas=  DB::select(HPMEConstants::PLN_METAS_POR_PROYECTO,array('ideProyecto'=>$ideProyecto));
        //Log::info($metas);
        $result=array();
        foreach($metas as $meta){
            $objetivos=$this->obtenerObjetivos($meta->ide_proyecto_meta,$ideProyectoRegion);//DB::select(HPMEConstants::PLN_OBJETIVOS_POR_META,array('ideProyectoMeta'=>$meta->ide_proyecto_meta));
            //Log::info("## objetivos ".count($objetivos));
            //Log::info($objetivos);
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
            $indicadores=$this->obtenerIndicadores($area->ide_area_objetivo,$ideProyectoRegion);
            if(!empty($indicadores)){
                $result[]=array('area'=>$area,'indicadores'=>$indicadores);
            }   
        }
        return $result; 
    }
    
    private function obtenerIndicadores($ideAreaObjetivo,$ideProyectoRegion=null){
        //Log::info('Obteniendo indicadores...');
        $indicadores=DB::select(HPMEConstants::PLN_INDICADORES_POR_AREA,array('ideAreaObjetivo'=>$ideAreaObjetivo));
        $result=array();
        foreach ($indicadores as $indicador){
            $productos=$this->obtenerProductos($indicador->ide_indicador_area,$ideProyectoRegion);
            if(!empty($productos)){
                $result[]=array('indicador'=>$indicador,'productos'=>$productos);
            }
        }
        return $result;     
    }
    
    private function obtenerProductos($ideIndicadorArea,$ideProyectoRegion=null){
        //Log::info('Obteniendo productos...');
        $productos=DB::select(HPMEConstants::PLN_PRODUCTOS_POR_INDICADOR,array('ideIndicadorArea'=>$ideIndicadorArea));    
        $result=array();
        foreach($productos as $producto){
            $detalle=$this->obtenerDetalleProductoRegion($producto->ide_producto_indicador, $ideProyectoRegion);
            //Log::info("### indicador area $producto->ide_producto_indicador region $ideProyectoRegion");
            //Log::info($detalle);
            if(!empty($detalle)){
                $result[]=array('producto'=>$producto,'detalles'=>$detalle);
            }          
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
            //Log::info($detalleProducto);
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
        if(!is_null($proyectoRegion) && ($rol=='COORDINADOR' || $rol == 'AFILIADO' || $ingresaPlan || $consultaPlanificacion)){   
            if($rol=='AFILIADO' || ($ingresaPlan && (!$consultaPlanificacion))){
                $ideRegion=$this->regionUsuario();
                if(is_null($ideRegion)){
                    return view('home');
                }else{
                    if($ideRegion!=$proyectoRegion->ide_region){
                        return view('home');
                    }
                }
            }
            //Log::info("Proyecto region plan ".$proyectoRegion->ide_proyecto_planificacion);
            $proyectoPlanificacion = PlnProyectoPlanificacion::find($proyectoRegion->ide_proyecto_planificacion);
            //Log::info($proyectoPlanificacion->descripcion);
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
                //$sheet->freezeFirstRowAndColumn();
                //$sheet->setFreeze('D2');
            });
            })->export('xls');
        
            //return view('planificacion_region_export',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region,'estado'=>$proyectoRegion->estado,'ingresaPlan'=>FALSE));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
        }else{
            return view('home');
        }      
    }
    
}
