<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\HPMEConstants;
use App\MonProyectoPeriodo;
use App\PrivilegiosConstants;
use App\CfgListaValor;

class MonitoreoProyecto extends Controller
{
    public function index(){
        $proyectos=  PlnProyectoPlanificacion::orderBy('fecha_proyecto', 'desc')->get();
        if(!$this->vistaPrivilegio()){
            return view('home');
        }
        return view('monitoreo_admon_proyectos',array('items'=>$proyectos));
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
        $periodo->estado=HPMEConstants::ABIERTO;
        $periodo->save();
        return response()->json();
    }


    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::MONITEO_ADMINISTRACION, $privilegios)){
                return TRUE;
            }
        }  
        return FALSE;
    }
    
    
}