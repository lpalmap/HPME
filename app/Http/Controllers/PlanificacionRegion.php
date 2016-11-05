<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\HPMEConstants;
use App\PlnProyectoPlanificacion;
use App\PlnProyectoRegion;
use Illuminate\Support\Facades\Log;


class PlanificacionRegion extends Controller
{    
    public function planificacionRegion(){
        $ultimoProyecto=PlnProyectoPlanificacion::where(array('estado'=>  HPMEConstants::ABIERTO))->pluck('ide_proyecto')->first();
        Log::info("ultimo ".$ultimoProyecto);
        if(!is_null($ultimoProyecto)){
            Log::info('No es null '.$ultimoProyecto);
            $regiones=  PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
            Log::info("count ".count($regiones));
            Log::info($regiones);
            foreach ($regiones as $region){
                Log::info('proyecto region: '.$region->ide_proyecto_region);
            }
        }
        
        return view('planificacionregion');
    }    
}
