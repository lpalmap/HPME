<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\PlnProyectoPlanificacion;
use App\PlnPresupuestoDepartamento;
use App\PlnPresupuestoColaborador;
use App\PlnColaboradorCuenta;
use App\PlnColaboradorCuentaDetalle;
use App\HPMEConstants;
use App\PlnProyectoPresupuesto;
use App\CfgCuenta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\PlnBitacoraPresupuesto;

class PresupuestoDepartamento extends Controller
{
    public function presupuestoDepartamento(){
        $rol=  request()->session()->get('rol');
        
        $ultimoProyecto=  PlnProyectoPresupuesto::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto_presupuesto','descripcion']);
        if(is_null($ultimoProyecto) || $rol!='DIRECTOR ADMIN Y FINANZAS'){
            return view('home');
        }
         //Log::info('No es null '.$ultimoProyecto);
        //$regionQuery=new PlnProyectoRegion();
        $regiones=  DB::select(HPMEConstants::PLN_PRESUPUESTOS_DEPARTAMENTOS,array('ideProyectoPresupuesto'=>$ultimoProyecto->ide_proyecto_presupuesto)); //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
        //Log::info("count ".count($regiones));
        //Log::info($regiones);
    //            foreach ($regiones as $region){
    //                Log::info('proyecto region: '.$region->ide_proyecto_region);
    //            }
        if(count($regiones)>0){
            return view('presupuestos',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyecto'=>$ultimoProyecto->ide_proyecto));
        }
        return view('presupuestos');
    }
    
    
    public function enviarPresupuesto(Request $request){
        $rol=  request()->session()->get('rol');
        if($rol!='DIRECTOR DEPARTAMENTO'){
            return response()->json(array('error'=>'Solo los directores pueden enviar presupuesto.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        //Log::info($request->ide_presupuesto_departamento);
        $presupuestoDepartamento=  PlnPresupuestoDepartamento::find($request->ide_presupuesto_departamento);
        //Log::info($presupuestoDepartamento);
        $proyecto= PlnProyectoPresupuesto::find($presupuestoDepartamento->ide_proyecto_presupuesto);
        if($proyecto->estado!=HPMEConstants::PUBLICADO){
            return response()->json(array('error'=>'Solo se pueden enviar presupuestos si el proyecto esta PUBLICADO.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $departamento=$this->departamentoDirector();
        if(!is_null($departamento)){
            if($departamento==$presupuestoDepartamento->ide_departamento){
                if($presupuestoDepartamento->estado!='ABIERTO'){
                    return response()->json(array('error'=>'Solo se pueden enviar presupuesto si el presupuesto del despartamento esta '.HPMEConstants::ABIERTO), HPMEConstants::HTTP_AJAX_ERROR);
                }
                $presupuestoDepartamento->estado=  HPMEConstants::ENVIADO;
                $presupuestoDepartamento->save();
                return response()->json();
            }
        }     
    }
    
    public function aprobarPresupuesto(Request $request){
        //Log::info("###########################");
        $planificacion= PlnPresupuestoDepartamento::find($request->ide_presupuesto_departamento);
        //Log::info($planificacion);
        if(!is_null($planificacion)){
            $count= PlnBitacoraPresupuesto::where(array('ide_presupuesto_departamento'=>$request->ide_presupuesto_departamento,'estado'=>  HPMEConstants::ABIERTO))->count();
            if(!is_null($count) && $count>0){
                return response()->json(array('error'=>'El presupuesto tiene observaciones pendentes, debe marcarlas como resueltas para aprobar.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($planificacion->estado==HPMEConstants::APROBADO){
                return response()->json(array('error'=>'Ya se encuentra aprobado el presupuesto para el departamento.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($planificacion->estado==HPMEConstants::ABIERTO){
                return response()->json(array('error'=>'El presupuesto se encuentra en estado '.HPMEConstants::ABIERTO.' no se ha enviado para su revisi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            //Log::info("cambiar estado....");
            $planificacion->estado=  HPMEConstants::APROBADO;
            $planificacion->save();
            return response()->json();
        }
    }
    
    private function departamentoDirector(){
        $user=Auth::user();       
        $regiones=DB::select(HPMEConstants::PLN_DEPARTAMENTO_POR_USUARIO,array('ideUsuario'=>$user->ide_usuario));
        if(count($regiones)>0){
            return $regiones[0]->ide_departamento;
        }else{
            return null;
        }
        
    }
    
    //Obtiene las plantillas de planificacion
    public function index(){
        $data= PlnProyectoPresupuesto::all(); 
        $rol=  request()->session()->get('rol');
        return view('proyectopresupuesto',array('items'=>$data,'rol'=>$rol));
    }
    
    public function retriveDepartamentos($ideProyectoPresupuesto){
        $ideDepartamento=$this->regionDirector();  
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
        $colaboradores=DB::select(HPMEConstants::PLN_PRESUPUESTO_COLABORADORES_DEPARTAMENTO,array('ideDepartamento'=>$ideDepartamento,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento));
        return $colaboradores;    
    } 

    public function deleteColaborador($idePresupuestoColaborador){
        $colaborador=  PlnPresupuestoColaborador::destroy($idePresupuestoColaborador);
        return response()->json($colaborador);        
    }
    
    public function colaboradorCuenta($idePresupuestoColaborador,$id=null){
        $cuentas=array();
        $ideCuentaPadre=0;
        $parents=array();
        $detalleColaborador=DB::select(HPMEConstants::PLN_PRESUPUESTO_DETALLE_COLABORADOR,array('idePresupuestoColaborador'=>$idePresupuestoColaborador));
        $idePresupuestoDepartamento=0;
        $ideProyectoPresupuesto=0;
        $nombreColaborador='Colaborador';
        if(count($detalleColaborador)>0){
            $idePresupuestoDepartamento=$detalleColaborador[0]->ide_presupuesto_departamento;
            $ideProyectoPresupuesto=$detalleColaborador[0]->ide_proyecto_presupuesto;
            $nombreColaborador=$detalleColaborador[0]->nombres." ".$detalleColaborador[0]->apellidos;
        }
        $cuentasIngresadas=array();
        if(is_null($id)){
            $cuentas= CfgCuenta::where(array('ide_cuenta_padre'=>null,'estado'=>HPMEConstants::ACTIVA))->get();
            $cuentasCompletadas=DB::select(HPMEConstants::PLN_PRESUPUESTO_CUENTA_COMPLETADA_RAIZ,array('idePresupuestoColaborador'=>$idePresupuestoColaborador));
            foreach($cuentasCompletadas as $ingresada){
                $cuentasIngresadas[]=$ingresada->ide_cuenta;
            }
        }else{
            //$cuentas= CfgCuenta::where(array('ide_cuenta_padre'=>$id,'estado'=>HPMEConstants::ACTIVA))->get();
            $cuentas=DB::select(HPMEConstants::PLN_CUENTAS_HIJAS_ACTIVAS,array('ideCuentaPadre'=>$id));
            $ideCuentaPadre=$id;
            $parents=DB::select(HPMEConstants::CFG_CUENTAS_PARENT,array('ideCuenta'=>$id));
            $cuentasCompletadas=DB::select(HPMEConstants::PLN_PRESUPUESTO_CUENTAS_COMPLETADAS,array('idePresupuestoColaborador'=>$idePresupuestoColaborador,'ideCuenta'=>$id));
            foreach($cuentasCompletadas as $ingresada){
                $cuentasIngresadas[]=$ingresada->ide_cuenta;
            }
        }
        return view('presupuesto_colaborador_cuenta',array('idePresupuestoColaborador'=>$idePresupuestoColaborador,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento,'ideProyectoPresupuesto'=>$ideProyectoPresupuesto,'nombreColaborador'=>$nombreColaborador,'cuentas'=>$cuentas,'ideCuentaPadre'=>$ideCuentaPadre,'parents'=>$parents,'ingresadas'=>$cuentasIngresadas));
    }


    private function crearProyectoPresupuesto(PlnProyectoPlanificacion $p){
        $presupuesto=new PlnProyectoPresupuesto;
        $presupuesto->fecha_proyecto=$p->fecha_proyecto;
        $presupuesto->descripcion=$p->descripcion;
        $presupuesto->estado=$p->estado;
        $presupuesto->ide_proyecto_planificacion=$p->ide_proyecto;
        $presupuesto->create($presupuesto->toArray());
    }
    
    public function addColaborador(Request $request){
        $idePresupuestoDepartamento=$request->ide_presupuesto_departamento;      
        $this->validateRequest($request, $idePresupuestoDepartamento);
        $colaborador=new PlnPresupuestoColaborador();
        $colaborador->fecha_ingreso=date(HPMEConstants::DATE_FORMAT,  time());
        $colaborador->ide_colaborador=$request->ide_colaborador;
        $colaborador->ide_presupuesto_departamento=$idePresupuestoDepartamento;
        $colaborador->save();
        $colaborador->colaborador;
        $result=array('ide_presupuesto_colaborador'=>$colaborador->ide_presupuesto_colabo,'fecha_ingreso'=>$colaborador->fecha_ingreso,'nombres'=>$colaborador->colaborador->nombres,'apellidos'=>$colaborador->colaborador->apellidos);
        return response()->json($result);
    }
    
    public function validateRequest($request,$idePresupuestoDepartamento){
        $rules=[
            'ide_colaborador' => 'unique:pln_presupuesto_colaborador,ide_colaborador,NULL,ide_colaborador,ide_presupuesto_departamento,'.$idePresupuestoDepartamento,
        ];
        $messages=[
            'unique' => 'El colaborador ya fue agregado al presupuesto del departamento.'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    public function addDetalleCuenta(Request $request){
        $items=$request->items['items'];
        $ideColaboradorCuenta=$this->cuentaColaborador($request->ide_cuenta, $request->ide_presupuesto_colaborador);
        $detalles=array();
        if(is_null($ideColaboradorCuenta)){
            $nuevoColaboradorCuenta= new PlnColaboradorCuenta();
            $nuevoColaboradorCuenta->ide_cuenta=$request->ide_cuenta;
            $nuevoColaboradorCuenta->ide_presupuesto_colaborador=$request->ide_presupuesto_colaborador;
            $nuevoColaboradorCuenta->save();
            $ideColaboradorCuenta=$nuevoColaboradorCuenta->ide_colaborador_cuenta;           
        }else{
            $detalles=DB::select(HPMEConstants::PLN_COLABORADOR_CUENTA_DETALLE,array('ideColaboradorCuenta'=>$ideColaboradorCuenta));
        }
        $ideColaboradorCuentaDetalle;
        $detallesPersistidos=array();
        foreach ($items as $item){
            $numItem=str_replace('itemVal', "", $item['item']);
            $itemValue=$item['value'];
            $ideColaboradorCuentaDetalle=$this->cuentaColaboradorDetalle($detalles, $numItem);
            if(is_null($ideColaboradorCuentaDetalle)){
                $nuevoDetalle=new PlnColaboradorCuentaDetalle();
                $nuevoDetalle->ide_colaborador_cuenta=$ideColaboradorCuenta;
                $nuevoDetalle->num_detalle=$numItem;
                $nuevoDetalle->valor=$itemValue;
                $nuevoDetalle->save();
            }else{
                $detalleCuenta=PlnColaboradorCuentaDetalle::find($ideColaboradorCuentaDetalle);
                $detalleCuenta->valor=$itemValue;
                $detalleCuenta->save();
            } 
           $detallesPersistidos[]=$numItem;
        }
        $this->eliminarDetallesNoUtilizados($detalles, $detallesPersistidos);  
        return response()->json(array('SUCCESS'=>true));
    }
    
    
    private function cuentaColaborador($ideCuenta,$idePresupuestoColaborador){
        $cuentas=DB::select(HPMEConstants::PLN_COLABORADOR_CUENTA,array('ideCuenta'=>$ideCuenta,'idePresupuestoColaborador'=>$idePresupuestoColaborador));
        if(count($cuentas)>0){
            return $cuentas[0]->ide_colaborador_cuenta;
        }else{
            return null;
        }     
    }
    
    private function cuentaColaboradorDetalle($detalles,$numDetalle){
        $numDetalle=$numDetalle.intValue();
        foreach ($detalles as $detalle){
            if($detalle->num_detalle==$numDetalle){
                return $detalle->ide_colaborador_cuenta_detalle;
            }
        }
        return null;
    }
    
    public function eliminarDetallesNoUtilizados($detalles,$detallesPersistidos){
        foreach($detalles as $detalle){
            if(!in_array($detalle->num_detalle,$detallesPersistidos)){
                PlnColaboradorCuentaDetalle::destroy($detalle->ide_colaborador_cuenta_detalle);
            }
        }      
    }
    
    public function getDetalleCuenta(Request $request){
        $ideCuenta=$request->ide_cuenta;
        $idePresupuestoColaborador=$request->ide_presupuesto_colaborador;
        $result=DB::select(HPMEConstants::PLN_COLABORADOR_CUENTA_DETALLE_VALORES,array('ideCuenta'=>$ideCuenta,'idePresupuestoColaborador'=>$idePresupuestoColaborador));
        return response()->json($result);
    }
    
    public function deletePresupuestoColaborador(Request $request){
        $idePresupuestoColaborador=$request->ide_presupuesto_colaborador;
        $presupuestoColaborador=  PlnPresupuestoColaborador::find($idePresupuestoColaborador);
        DB::statement(HPMEConstants::PLN_PRESUPUESTO_ELIMINAR_DETALLE_CUENTA_COLABORADOR,array('idePresupuestoColaborador'=>$presupuestoColaborador->ide_presupuesto_colaborador));
        DB::statement(HPMEConstants::PLN_PRESUPUESTO_ELIMINAR_CUENTAS_COLABORADOR,array('idePresupuestoColaborador'=>$presupuestoColaborador->ide_presupuesto_colaborador));
        PlnPresupuestoColaborador::destroy($presupuestoColaborador->ide_presupuesto_colaborador);
        return response()->json($presupuestoColaborador);
    }
    
    
}