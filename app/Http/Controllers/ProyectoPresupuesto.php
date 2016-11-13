<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\PlnPresupuestoDepartamento;
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
    
    public function retriveDepartamentos($ideProyectoPresupuesto){
        $ideDepartamento=$this->regionDirector();
        Log::info('Region director '.$ideDepartamento);   
        $items=array();
        if(!is_null($ideDepartamento)){
            $presupuestos=DB::select(HPMEConstants::PLN_PRESUPUESTO_POR_DEPARTAMENTO,array('ideProyectoPresupuesto'=>$ideProyectoPresupuesto,'ideDepartamento'=>$ideDepartamento));
            if(count($presupuestos)>0){
                return view('presupuesto_departamentos',array('items'=>$presupuestos));
            }else{
                $presupuesto=new PlnPresupuestoDepartamento();
                $presupuesto->fecha_ingreso=date(HPMEConstants::DATE_FORMAT,  time());
                $presupuesto->estado= HPMEConstants::ABIERTO;
                $presupuesto->ide_proyecto_presupuesto=$ideProyectoPresupuesto;
                $presupuesto->ide_departamento=$ideDepartamento;
                $presupuesto->save();
                $items=DB::select(HPMEConstants::PLN_PRESUPUESTO_POR_DEPARTAMENTO,array('ideProyectoPresupuesto'=>$ideProyectoPresupuesto,'ideDepartamento'=>$ideDepartamento));
            }
        }       
        Log::info($items);
        return view('presupuesto_departamentos',array('items'=>$items));
    }
    
    
    private function regionDirector(){
        $user=Auth::user();       
        $regiones=DB::select(HPMEConstants::PLN_DEPARTAMENTO_POR_USUARIO,array('ideUsuario'=>$user->ide_usuario));
        if(count($regiones)>0){
            return $regiones[0]->ide_departamento;
        }else{
            return null;
        }
        
    }
    
    public function retriveColaboradores($idePresupuestoDepartamento){
        $departamento=PlnPresupuestoDepartamento::find($idePresupuestoDepartamento);
        if(!is_null($departamento)){
            $ideProyectoPresupuesto=$departamento->ide_proyecto_presupuesto;
            //Validación departamento/director
            $colaboradores=DB::select(HPMEConstants::PLN_PRESUPUESTO_COLABORADOR_DEPARTAMENTO,array('idePresupuestoDepartamento'=>$idePresupuestoDepartamento));
            return view('presupuesto_colaborador',array('ideProyectoPresupuesto'=>$ideProyectoPresupuesto,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento,'items'=>$colaboradores));
        }
        return view('home');
    }

    //Devuelve la lista de colaboradores que se pueden agregar al presupuesto del departamento
    public function retriveAllColaboradores(Request $request){
        $idePresupuestoDepartamento=$request->ide_presupuesto_departamento;
        $ideDepartamento=  PlnPresupuestoDepartamento::where('ide_presupuesto_departamento',$idePresupuestoDepartamento)->pluck('ide_departamento')->first();
        Log::info('OOO... departamento... '.$ideDepartamento);
        $colaboradores=DB::select(HPMEConstants::PLN_PRESUPUESTO_COLABORADORES_DEPARTAMENTO,array('ideDepartamento'=>$ideDepartamento,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento));
        Log::info($colaboradores);
        return $colaboradores;    
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