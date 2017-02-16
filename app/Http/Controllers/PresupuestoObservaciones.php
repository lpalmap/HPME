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
use App\PrivilegiosConstants;
use App\CfgParametro;
use Mail;


class PresupuestoObservaciones extends Controller
{    
    
    public function observacionesDepartamento($id){
        $rol=  request()->session()->get('rol');
        $vistaPrivilegio=$this->vistaPrivilegio();
        $esContador=$this->esContador();
        $ingresaPresupuesto=$this->ingresarPresupuesto();
        if($ingresaPresupuesto || $vistaPrivilegio || $esContador){
            $presupuesto= PlnPresupuestoDepartamento::find($id);
            if(is_null($presupuesto)){
                return view('home');
            }
            if(!$this->verificarContador($presupuesto->ide_departamento)){
                return view('home');
            }
            $administradorDepartamento=false;
            if($ingresaPresupuesto && !$vistaPrivilegio){
                if(!$this->departamentoDirector($presupuesto->ide_departamento)){
                    return view ('home');
                }else{
                    $administradorDepartamento=true;
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
            $aprueba=$this->apruebaPrivilegio();
            
            $myusuario=Auth::user();
            $emails=  array();
            if(!is_null($bitacora)){
                $emails=DB::select(HPMEConstants::PLN_USUARIOS_BITACORA_PRESUPUESTO,array('ideBitacora'=>$bitacora->ide_bitacora_presupuesto,'myUsuario'=>$myusuario->ide_usuario));            
            }
            $cadenaCorreos='';
            $first=true;
            
            $emailTarget='';
            if($administradorDepartamento){
                //Se busca el correo del coordinar de monitoreo
                $emailTarget=$this->emailPresupuesto();
            }else{
                //se coloca por defecto el correo del administrador de la region
                $emailTarget=$this->emailAdministradorDepartamento($presupuesto->ide_departamento);             
            }  
            $incluirEmailTarjet=true;
            foreach($emails as $email){
                if($email->email===$emailTarget){
                    $incluirEmailTarjet=false;
                }
                if($first){
                    $first=false;
                    $cadenaCorreos=$email->email;
                }else{
                    $cadenaCorreos=$cadenaCorreos.','.$email->email;
                }
            }
            if($incluirEmailTarjet){
                if($first){
                    $cadenaCorreos=$emailTarget;
                }else{
                    $cadenaCorreos=$emailTarget.','.$cadenaCorreos;
                }
                
            }  
            
            
            return view('observaciones_presupuesto',array('idePresupuestoDepartamento'=>$id,'estado'=>$presupuesto->estado,'rol'=>$rol,'nombreProyecto'=>$nombreProyecto,'nombre'=>$nombreDepartamento,'bitacora'=>$bitacora,'mensajes'=>$mensajes,'usuario'=>$usuarioPrimerMensaje,'estadoBitacora'=>$estadoBitacora,'aprueba'=>$aprueba,'correos'=>$cadenaCorreos));
        }
        return view('home');
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
    
    private function emailAdministradorDepartamento($ideDepartamento){
        $emails=DB::select(HPMEConstants::PLN_OBSERVACIONES_PRESUPUESTO_EMAIL_ADMINISTRADOR,array('ideDepartamento'=>$ideDepartamento));
        if(count($emails)>0){
            return $emails[0]->email;
        } 
    }

    private function emailPresupuesto(){
        $email= CfgParametro::where('nombre','=', HPMEConstants::PARAM_EMAIL_PRESUPUESTO)->pluck('valor')->first();
        return $email;
    }
    
    private function vistaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_TODOS_LOS_DEPARTAMENTOS, $privilegios)
                    || in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS, $privilegios)
                    ){
                return TRUE;
            }
        }  
        return FALSE;
    }
    
    private function verificarContador($ideDepartamento){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_TODOS_LOS_DEPARTAMENTOS, $privilegios)
                    || in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS,$privilegios)
                    ){
                return TRUE;
            }
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO, $privilegios)){
                $user=Auth::user();
                $count=  CfgDepartamento::where(array('ide_usuario_contador'=>$user->ide_usuario,'ide_departamento'=>$ideDepartamento))->count();
                if($count>0){
                    return TRUE;
                }
                FALSE;
            }
        } 
        return TRUE;
    }
    
    public function apruebaPrivilegio(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_APROBACION_PRESUPUESTOS,$privilegios)
                    ){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    private function bitacoraPorProyectoDepartamento($idePresupuestoDepartamento){
        $bitacoras= PlnBitacoraPresupuesto::where('ide_presupuesto_departamento','=',$idePresupuestoDepartamento)->get();
        if(count($bitacoras)>0){
            return $bitacoras[0];
        }
        return null;
    }
    
    private function esContador(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO, $privilegios)){   
                return TRUE;
            }
        } 
        return FALSE;
    }
    
    public function addMessage(Request $request){
        $rol=  request()->session()->get('rol');
        $vistaPrivilegio=$this->vistaPrivilegio();
        $esContador=$this->esContador();
        $ingresaPresupuesto=  $this->ingresarPresupuesto();
        if($ingresaPresupuesto || $vistaPrivilegio || $esContador){
            //Log::info("Buscando ".$request->ide_presupuesto_departamento);
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
            $aprueba=$this->apruebaPrivilegio();
            if($aprueba){
                //$proyectoRegion=  PlnProyectoRegion::find($request->ide_proyecto_region);
                if($presupuestoDepartamento->estado==HPMEConstants::ENVIADO){
                    $presupuestoDepartamento->estado=HPMEConstants::ABIERTO;
                    $presupuestoDepartamento->save();
                    $bitacora->estado=  HPMEConstants::ABIERTO;
                    $bitacora->save();
                    $cambioEstado=  HPMEConstants::SI;
                }
            }
            try{
                $this->enviarNotificacion($request->asunto, $request->para,$request->mensaje);
            } catch(\Exception $e){
                //Si ocurre un error al enviar el correo se ignora y se agrega el mensaje en bitacora de todos modos
            } 
            return response()->json(array('ide_usuario'=>$user->ide_usuario,'usuario'=>$user->usuario,'nombres'=>$user->nombres,'apellidos'=>$user->apellidos,'cambioEstado'=>$cambioEstado));
        }
    }
    
    private function enviarNotificacion($asunto,$para,$mensaje){
        $emails=  explode(",", $para);
        if(strlen($para)>0 && !is_null($emails) && count($emails)>0){
            $user=Auth::user();
            Mail::send('emails.reminder', ['title' => 'Presupuesto', 'content' => $mensaje], function ($message) use ($user,$asunto,$emails)
            {
                $message->from(env('MAIL_USERNAME'), $user->nombres.' '.$user->apellidos);
                $message->to($emails);              
                $message->subject($asunto);

            });
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
}