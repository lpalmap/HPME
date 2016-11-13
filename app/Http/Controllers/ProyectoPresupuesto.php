<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;
use App\HPMEConstants;
use App\PlnProyectoPresupuesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProyectoPresupuesto extends Controller
{
    //Obtiene las plantillas de planificacion
    public function index(){
        $data= PlnProyectoPresupuesto::all(); 
        $rol=  request()->session()->get('rol');
        return view('proyectopresupuesto',array('items'=>$data,'rol'=>$rol));
    }
    
    public function retriveDepartamentos(){
        $user=Auth::user();
               
    }
    
    private function regionDirector(){
        
    }


    private function crearProyectoPresupuesto(PlnProyectoPlanificacion $p){
        Log::info($p);
        $presupuesto=new PlnProyectoPresupuesto;
        $presupuesto->fecha_proyecto=$p->fecha_proyecto;
        $presupuesto->descripcion=$p->descripcion;
        $presupuesto->estado=$p->estado;
        $presupuesto->ide_proyecto_planificacion=$p->ide_proyecto;
        $presupuesto->create($presupuesto->toArray());
    }
}