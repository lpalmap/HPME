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
use App\PlnProyectoPresupuesto;
use App\MonArchivoPresupuesto;


class MonitoreoProyecto extends Controller
{
    public function index(){
        $proyectos=  PlnProyectoPlanificacion::orderBy('fecha_proyecto', 'desc')->get();
        if(!$this->vistaPrivilegio()){
            return view('home');
        }
        return view('monitoreo_admon_proyectos',array('items'=>$proyectos));
    }
    
    public function monitoreoProyecto(){
        $ultimoProyecto=PlnProyectoPlanificacion::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto','descripcion','estado']);
        $consulta=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if(!is_null($ultimoProyecto) && ($apruebaPlanificacion || $consulta)){
            $puedeCerrar=$apruebaPlanificacion;
            $regiones=  DB::select(HPMEConstants::MONITOREO_REGION_QUERY,array('ideProyecto'=>$ultimoProyecto->ide_proyecto,'estado'=>  HPMEConstants::PUBLICADO)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            if(count($regiones)>0){
                return view('monitoreo_regiones',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyecto'=>$ultimoProyecto->ide_proyecto,'estado'=>$ultimoProyecto->estado,'puedeCerrar'=>$puedeCerrar));
            }          
        }        
        return view('monitoreo_regiones');
    }
    
    public function adminProyecto($ideProyecto){
        if(!$this->vistaPrivilegio()){
            return view('home');
        }
        $proyecto=  PlnProyectoPlanificacion::find($ideProyecto);
        $periodos=  MonProyectoPeriodo::where('ide_proyecto','=',$ideProyecto)->orderBy('no_periodo','asc')->get();   
        return view('monitoreo_admon_proyecto',array('proyecto'=>$proyecto->descripcion,'ideProyecto'=>$proyecto->ide_proyecto,'items'=>$periodos,'estado'=>$proyecto->estado));
    }
    
    public function iniciarMonitoreo(Request $request){
        $proyecto=  PlnProyectoPlanificacion::find($request->ide_proyecto);
        if($proyecto->estado!=='CERRADO'){
            return response()->json(array('error'=>'El proyecto debe estar cerrado para iniciar el monitoreo'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        //se obtiene la periocidad del proyecto para determinar el numero de elementos de monitoreo que se deben crear.
        $proyecto->periodiciad;
        $periodos=  CfgListaValor::where('grupo_lista','=',$proyecto->periodicidad->codigo_lista)->orderBy('codigo_lista','asc')->get();
        MonProyectoPeriodo::where('ide_proyecto',$proyecto->ide_proyecto)->delete();
        foreach ($periodos as $periodo) {
            $periodoMonitoreo=  new MonProyectoPeriodo;
            $periodoMonitoreo->estado=  HPMEConstants::INACTIVO;
            $periodoMonitoreo->no_periodo=$periodo->codigo_lista;
            $periodoMonitoreo->ide_proyecto=$proyecto->ide_proyecto;
            $periodoMonitoreo->descripcion=$periodo->descripcion;
            $periodoMonitoreo->create($periodoMonitoreo->toArray());
        }
        $proyecto->estado=  HPMEConstants::MONITOREO;
        $proyecto->save();
        
        return response()->json();
    }
    
    public function habilitarPeriodo(Request $request){
        $periodo=  MonProyectoPeriodo::find($request->ide_periodo_monitoreo);
//        $count=  MonProyectoPeriodo::where('ide_proyecto',$periodo->ide_proyecto)->where('estado',  HPMEConstants::ABIERTO)->count();
//        if(count>0){
//            return response()->json(array('error'=>'El proyecto debe estar cerrado para iniciar el monitoreo'), HPMEConstants::HTTP_AJAX_ERROR);
//        }else{
//            
//        }
        $periodo->estado=HPMEConstants::PUBLICADO;
        $periodo->fecha_habilitacion=date(HPMEConstants::DATE_FORMAT,  time());
        $periodo->save();
        return response()->json();
    }


    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::MONITOREO_ADMINISTRACION, $privilegios)){
                return TRUE;
            }
        }  
        return FALSE;
    }
    
    private function vistaContador(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO, $privilegios)){
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
    
    private function apruebaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFICACION_APROBAR_PLANIFICACION, $privilegios)){
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
    
    public function monitoreoAfiliado(){
        $proyectos=  PlnProyectoPlanificacion::orderBy('fecha_proyecto', 'desc')->get();
        if(!$this->ingresoMonitoreo()){
            return view('home');
        }
        return view('monitoreo_afiliado_proyectos',array('items'=>$proyectos));
    }
    
    public function monitoreoAfiliadoProyecto($ideProyecto){
        $regionUsuario=$this->regionUsuario();        
        if(!$this->ingresoMonitoreo() || is_null($regionUsuario)){
            return view('home');
        }
        $nombreRegion=  CfgRegion::where('ide_region','=',$regionUsuario)->pluck('nombre')->first();
        $proyecto=  PlnProyectoPlanificacion::find($ideProyecto);//proyecto anual
        $ideProyectoRegion=PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$proyecto->ide_proyecto,'ide_region'=>$regionUsuario))->pluck('ide_proyecto_region')->first(); //proyecto especifico de la region
        //$periodos=  MonProyectoPeriodo::where('ide_proyecto','=',$ideProyecto)->where('estado','!=','INACTIVO')->orderBy('no_periodo','asc')->get();       
        $periodos=  MonProyectoPeriodo::where('ide_proyecto','=',$ideProyecto)->where('estado','!=','INACTIVO')->orderBy('no_periodo','asc')->pluck('ide_periodo_monitoreo');
        $periodosRegion=array();
        foreach ($periodos as $periodo){
            $region=  MonPeriodoRegion::where(array('ide_periodo_monitoreo'=>$periodo,'ide_proyecto_region'=>$ideProyectoRegion))->first();
            if(is_null($region)){
                $region=new MonPeriodoRegion;
                $region->estado=  HPMEConstants::ABIERTO;
                $region->ide_periodo_monitoreo=$periodo;
                $region->ide_proyecto_region=$ideProyectoRegion;
                //$region=$region->create($region->toArray());
                $region=$region->create($region->toArray());
                $region->periodo();
                $periodosRegion[]=$region;
            }else{
                $region->periodo;
                $periodosRegion[]=$region;
            }          
        }
        Log::info("TEstsf");
                Log::info($periodosRegion);
        return view('monitoreo_afiliado_proyecto',array('proyecto'=>$proyecto->descripcion,'ideProyecto'=>$proyecto->ide_proyecto,'items'=>$periodosRegion,'region' =>$nombreRegion));
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
    
    public function proyectosContador(){
        $proyectos= PlnProyectoPresupuesto::orderBy('fecha_proyecto', 'desc')->get();
        if(!$this->vistaContador()){
            return view('home');
        }
        return view('monitoreo_proyectos_contador',array('items'=>$proyectos));
    }
    
    public function periodosContador($ideProyecto){
        if(!$this->vistaContador()){
            return view('home');
        }
        $proyecto=  PlnProyectoPlanificacion::find($ideProyecto);
        $periodos=  MonProyectoPeriodo::where('ide_proyecto','=',$ideProyecto)->orderBy('no_periodo','asc')->get();   
        return view('monitoreo_periodos_ejecucion',array('proyecto'=>$proyecto->descripcion,'ideProyecto'=>$proyecto->ide_proyecto,'items'=>$periodos,'estado'=>$proyecto->estado));
        
    }
    
    public function proyectoPeriodo($id){
        if(!$this->vistaContador()){
            return view('home');
        }        
        $user=Auth::user();
        $periodo=MonProyectoPeriodo::find($id);//where('ide_periodo_monitoreo','=',$id)->pluck('descripcion')->first();
        $archivos=  MonArchivoPresupuesto::where(array('ide_periodo_monitoreo'=>$id,'ide_usuario'=>$user->ide_usuario))->get();
        return view('monitoreo_periodo_presupuesto',array('archivos'=>$archivos,'periodo'=>$periodo->descripcion,'ideProyecto'=>$periodo->ide_proyecto,'idePeriodoMonitoreo'=>$id));
    }
    
    
    
}