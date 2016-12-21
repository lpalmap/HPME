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
use App\PlnBitacoraMensaje;
use App\PlnPresupuestoDepartamento;
use App\PlnBitacoraPresupuesto;
use App\PlnBitacoraMensajePresupuesto;
use App\PlnProyectoPresupuesto;
use App\CfgDepartamento;


class PresupuestoObservaciones extends Controller
{    
    
    public function observacionesDepartamento($id){
        $rol=  request()->session()->get('rol');
        if($rol=='DIRECTOR DEPARTAMENTO' || $rol=='AFILIADO' || $rol=='DIRECTOR ADMIN Y FINANZAS'){
            $presupuesto= PlnPresupuestoDepartamento::find($id);
            if(is_null($presupuesto)){
                return view('home');
            }
            if($rol=='DIRECTOR DEPARTAMENTO' || $rol=='AFILIADO'){
                if(!$this->departamentoDirector($presupuesto->ide_departamento)){
                    return view ('home');
                }
            }
            $bitacora=$this->bitacoraPorProyectoDepartamento($presupuesto->ide_presupuesto_departamento);
            $mensajes=array();
            $usuarioPrimerMensaje=-1;
            $estadoBitacora=null;
            if(!is_null($bitacora)){
                $mensajes=  PlnBitacoraMensajePresupuesto::with('usuario')->where('ide_bitacora_presupuesto','=',$bitacora->ide_bitacora_presupuesto)->get();
                if(count($mensajes)>0){
                    $usuarioPrimerMensaje=$mensajes[0]->ide_usuario;
                }
               //$estadoBitacora= PlnBitacoraPresupuesto::where('ide_bitacora_proyecto_region','=',$bitacora->ide_bitacora_proyecto_region)->pluck('estado')->first();
            
                $estadoBitacora=$bitacora->estado;
            }
             
            $nombreProyecto=  PlnProyectoPresupuesto::where('ide_proyecto_presupuesto','=',$presupuesto->ide_proyecto_presupuesto)->pluck('descripcion')->first();
            $nombreDepartamento=  CfgDepartamento::where('ide_departamento','=',$presupuesto->ide_departamento)->pluck('nombre')->first();
            
            return view('observaciones_presupuesto',array('idePresupuestoDepartamento'=>$id,'estado'=>$presupuesto->estado,'rol'=>$rol,'nombreProyecto'=>$nombreProyecto,'nombre'=>$nombreDepartamento,'bitacora'=>$bitacora,'mensajes'=>$mensajes,'usuario'=>$usuarioPrimerMensaje,'estadoBitacora'=>$estadoBitacora));
        }
        return view('home');
    }
    
    
    private function bitacoraPorProyectoDepartamento($idePresupuestoDepartamento){
        $bitacoras= PlnBitacoraPresupuesto::where('ide_presupuesto_departamento','=',$idePresupuestoDepartamento)->get();
        if(count($bitacoras)>0){
            return $bitacoras[0];
        }
        return null;
    }
    
    public function addMessage(Request $request){
        $rol=  request()->session()->get('rol');
        if($rol=='DIRECTOR DEPARTAMENTO' || $rol=='AFILIADO' || $rol=='DIRECTOR ADMIN Y FINANZAS'){
            Log::info("Buscando ".$request->ide_presupuesto_departamento);
            $presupuestoDepartamento= PlnPresupuestoDepartamento::find($request->ide_presupuesto_departamento);
            if(is_null($presupuestoDepartamento)){
                return response()->json(array('error'=>'No existe el presupuesto para el departamento.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($presupuestoDepartamento->estado==HPMEConstants::APROBADO){
                return response()->json(array('error'=>'No se puede agregar nuevos mensajes a un presupuesto aprobado.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            
            $bitacora=$this->bitacoraPorProyectoDepartamento($request->ide_presupuesto_departamento);
            $cambioEstado=  HPMEConstants::NO;
            if(is_null($bitacora)){
                $bitacora=new PlnBitacoraPresupuesto();
                $bitacora->ide_presupuesto_departamento=$request->ide_presupuesto_departamento;
                $bitacora->estado=HPMEConstants::ABIERTO;
                $bitacora->save();
                $cambioEstado=  HPMEConstants::SI;
            }
            $user=Auth::user();
            $bitacoraMensaje=new PlnBitacoraMensajePresupuesto();
            $bitacoraMensaje->ide_usuario=$user->ide_usuario;
            date_default_timezone_set(HPMEConstants::TIME_ZONE);
            $bitacoraMensaje->fecha=date(HPMEConstants::DATETIME_FORMAT,  time());
            $bitacoraMensaje->ide_bitacora_presupuesto=$bitacora->ide_bitacora_presupuesto;
            $bitacoraMensaje->mensaje=$request->mensaje;
            $bitacoraMensaje->save();      
            if($rol=='DIRECTOR ADMIN Y FINANZAS'){
                //$proyectoRegion=  PlnProyectoRegion::find($request->ide_proyecto_region);
                if($presupuestoDepartamento->estado==HPMEConstants::ENVIADO){
                    $presupuestoDepartamento->estado=HPMEConstants::ABIERTO;
                    $presupuestoDepartamento->save();
                    $bitacora->estado=  HPMEConstants::ABIERTO;
                    $bitacora->save();
                    $cambioEstado=  HPMEConstants::SI;
                }
            }
            return response()->json(array('ide_usuario'=>$user->ide_usuario,'usuario'=>$user->usuario,'nombres'=>$user->nombres,'apellidos'=>$user->apellidos,'cambioEstado'=>$cambioEstado));
        }
    }
    
    public function marcarBitacora(Request $request){
        $bitacora=$this->bitacoraPorProyectoDepartamento($request->ide_presupuesto_departamento);
        if(is_null($bitacora)){
            return response()->json(array('error'=>'No se entraron observaciones para el departamento.'), HPMEConstants::HTTP_AJAX_ERROR);
        }else{
            if($bitacora->estado==HPMEConstants::CERRADO){
                return response()->json(array('error'=>'Las observaciones ya fueron marcadas como resueltas.'), HPMEConstants::HTTP_AJAX_ERROR);
            }else{
                $bitacora->estado=  HPMEConstants::CERRADO;
                $bitacora->save();
                return response()->json();
            }
        }
    }
    
    public function planificacionRegion(){
        $ultimoProyecto=PlnProyectoPlanificacion::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto','descripcion']);
        //Log::info("ultimo ".$ultimoProyecto);
        $rol=  request()->session()->get('rol');
        if(!is_null($ultimoProyecto) && $rol=='COORDINADOR'){
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
        $rol=  request()->session()->get('rol');
        if(!is_null($ideProyecto) && $rol=='COORDINADOR'){
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
        if(!is_null($proyectoRegion)){
            if($rol=='AFILIADO'){
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
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
        }else{
            return view('home');
        }      
    }
    
    public function planificacionProyectoDetalle($id){ 
        $proyectoPlanificacion = PlnProyectoPlanificacion::find($id);     
        $rol=  request()->session()->get('rol');
        if(!is_null($proyectoPlanificacion) && $rol=='AFILIADO'){
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
                return view('planificacion_region_detalle',array('plantilla'=>array("proyecto"=>($proyectoPlanificacion->descripcion),'metas'=> array()),'region'=>$region->nombre,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol));
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
            
            return view('planificacion_region_detalle',array('plantilla'=>$plantilla,'region'=>$nombreRegion,'num_items'=>count($encabezados),'encabezados'=>$encabezados,'rol'=>$rol,'ideProyectoRegion'=>$proyectoRegion->ide_proyecto_region));
            //return view('planificacion_region_detalle',array('region'=>$nombreRegion));
        }else{
            return view('home');
        }      
    } 
    
    private function departamentoDirector($ideDepartamento){
        $user=Auth::user();       
        $regiones=CfgDepartamento::where(array('ide_usuario_director'=>$user->ide_usuario))->pluck('ide_departamento');//DB::select(HPMEConstants::PLN_DEPARTAMENTO_POR_USUARIO,array('ideUsuario'=>$user->ide_usuario));
        Log::info("#### validando $ideDepartamento");
        Log::info($regiones);
        foreach($regiones as $region){
            Log::info($region);
            if($region===$ideDepartamento){
                Log::info("$$$$$ true $region dep $ideDepartamento");
                return TRUE;
            }
        }
        return FALSE;        
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