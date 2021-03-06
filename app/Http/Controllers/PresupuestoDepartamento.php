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
use App\CfgDepartamento;
use App\PrivilegiosConstants;
use App\SegUsuario;
use Mail;

class PresupuestoDepartamento extends Controller
{
    public function presupuestoDepartamento(){
        $rol=  request()->session()->get('rol');
        
        $ultimoProyecto=  PlnProyectoPresupuesto::where('estado','!=',HPMEConstants::EJECUTADO)->first(['ide_proyecto_presupuesto','descripcion','estado']);
        //$privilegios=request()->session()->get('privilegios');
        //Log::info($privilegios);
        if(is_null($ultimoProyecto)){
            if(!$this->vistaPrivilegio()){
                return view('home');
            }           
        }
         //Log::info('No es null '.$ultimoProyecto);
        //$regionQuery=new PlnProyectoRegion();
        $puedeCerrar=$this->puedeCerrar();
        if($this->vistaContador()){
            $user=Auth::user();
            $regiones=  DB::select(HPMEConstants::PLN_PRESUPUESTOS_DEPARTAMENTOS_CONTADOR,array('ideProyectoPresupuesto'=>$ultimoProyecto->ide_proyecto_presupuesto,'ideUsuarioContador'=>$user->ide_usuario));
        }else{
            $regiones=  DB::select(HPMEConstants::PLN_PRESUPUESTOS_DEPARTAMENTOS,array('ideProyectoPresupuesto'=>$ultimoProyecto->ide_proyecto_presupuesto));
        }
         //PlnProyectoRegion::where(array('ide_proyecto_planificacion'=>$ultimoProyecto))->get(['ide_proyecto_planificacion','estado']);
        //Log::info("count ".count($regiones));
        //Log::info($regiones);
    //            foreach ($regiones as $region){
    //                Log::info('proyecto region: '.$region->ide_proyecto_region);
    //            }
        //Log::info($ultimoProyecto);
        if(count($regiones)>0){
            return view('presupuestos',array('regiones'=>$regiones,'proyecto'=>$ultimoProyecto->descripcion,'ideProyectoPresupuesto'=>$ultimoProyecto->ide_proyecto_presupuesto,'estado'=>$ultimoProyecto->estado,'puedeCerrar'=>$puedeCerrar));
        }
        return view('presupuestos');
    }
    
    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_TODOS_LOS_DEPARTAMENTOS, $privilegios)
                    || in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS, $privilegios)
                            || in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO, $privilegios)
                    ){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    private function vistaContador(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_TODOS_LOS_DEPARTAMENTOS, $privilegios)
                    || in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS, $privilegios)
                    ){
                return FALSE;
            }
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO, $privilegios)
                    ){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    
    public function puedeCerrar(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;   
    }
    
    private function ingresarPresupuesto(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_INGRESAR_PRESUPUESTO, $privilegios)
                    ){
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    public function enviarPresupuesto(Request $request){
        $rol=  request()->session()->get('rol');
        if(!$this->ingresarPresupuesto()){
            return response()->json(array('error'=>'Solo los directores pueden enviar presupuesto.'), HPMEConstants::HTTP_AJAX_ERROR);
        }   
   
        //Log::info($request->ide_presupuesto_departamento);
        $presupuestoDepartamento=  PlnPresupuestoDepartamento::find($request->ide_presupuesto_departamento);
        //Log::info($presupuestoDepartamento);
        $proyecto= PlnProyectoPresupuesto::find($presupuestoDepartamento->ide_proyecto_presupuesto);
        if($proyecto->estado!=HPMEConstants::PUBLICADO){
            return response()->json(array('error'=>'Solo se pueden enviar presupuestos si el proyecto esta PUBLICADO.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        if(!$this->departamentoDirector($presupuestoDepartamento->ide_departamento)){
            return response()->json(array('error'=>'Solo los directores del departamento pueden enviar presupuesto.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        if($presupuestoDepartamento->estado!='ABIERTO'){
            return response()->json(array('error'=>'Solo se pueden enviar presupuesto si el presupuesto del despartamento esta '.HPMEConstants::ABIERTO), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $presupuestoDepartamento->estado=  HPMEConstants::ENVIADO;
        $presupuestoDepartamento->save();
        return response()->json(); 
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
            date_default_timezone_set(HPMEConstants::TIME_ZONE);
            $planificacion->fecha_aprobacion=date(HPMEConstants::DATE_FORMAT,  time());
            $user=Auth::user();
            $planificacion->ide_usuario_aprobacion=$user->ide_usuario;
            $planificacion->save();
            
            try{
                $proyecto= PlnProyectoPresupuesto::where('ide_proyecto_presupuesto','=',$planificacion->ide_proyecto_presupuesto)->pluck('descripcion')->first();
                $departamento=  CfgDepartamento::find($planificacion->ide_departamento);
                $this->enviarNotificacionAprobado($proyecto.'/'.$departamento->nombre,$departamento->ide_usuario_director); 
            } catch(\Exception $e){
                //Si ocurre un error al enviar el correo se ignora y se agrega el mensaje en bitacora de todos modos
            }             
            return response()->json();
        }
    }
    
    private function enviarNotificacionAprobado($asunto,$usuario){
        $para=SegUsuario::where('ide_usuario','=',$usuario)->pluck('email')->first();
        $user=Auth::user();
        if(strlen($para)>0){   
            $mensaje='Felicidades!!! Su presupuesto para el proyecto '.$asunto.' ha sido aprobado.';
            Mail::send('emails.reminder', ['title' => 'Aprobaci&oacute;n Presupuesto', 'content' => $mensaje], function ($message) use ($user,$asunto,$para)
            {
                $message->from(env('MAIL_USERNAME'), $user->nombres.' '.$user->apellidos);
                $message->to(array($para));              
                $message->subject($asunto);

            });
        }     
    }
    
    private function departamentoDirector($ideDepartamento){
        $user=Auth::user();       
        $regiones=CfgDepartamento::where(array('ide_usuario_director'=>$user->ide_usuario))->pluck('ide_departamento');//DB::select(HPMEConstants::PLN_DEPARTAMENTO_POR_USUARIO,array('ideUsuario'=>$user->ide_usuario));
        foreach($regiones as $region){
            if($region===$ideDepartamento){
                return TRUE;
            }
        }
        return FALSE;        
    }
    
    //Obtiene las plantillas de planificacion
    public function index(){
        $data= PlnProyectoPresupuesto::all(); 
        $rol=  request()->session()->get('rol');
        return view('proyectopresupuesto',array('items'=>$data,'rol'=>$rol));
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
        //Log::info("### agregando detalles $ideColaboradorCuenta");
        //Log::info($items);
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
            //Log::info("Buscando detalle ".$detalle->num_detalle." param::: ".$numDetalle);
            if($detalle->num_detalle==$numDetalle){               
                return $detalle->ide_colaborador_cuenta_detalle;
            }
        }
        //Log::info("Return null");
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