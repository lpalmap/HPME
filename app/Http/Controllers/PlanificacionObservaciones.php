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
use App\PrivilegiosConstants;
use Mail;
use App\CfgParametro;


class PlanificacionObservaciones extends Controller
{    
    
    public function observacionesRegion($id){
        $rol=  request()->session()->get('rol');
        $ingresaPlan=$this->ingresoPlanificacion();
        $consultaPlanificacion=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if($apruebaPlanificacion || $ingresaPlan || $consultaPlanificacion){
            $proyectoRegion=  PlnProyectoRegion::find($id);
            if(is_null($proyectoRegion)){
                return view('home');
            }
            //Determina si el usuario logueado es el administrador de la region
            $administradorRegion=false;
            if($ingresaPlan && !$consultaPlanificacion){
                $ideRegion=$this->regionUsuario();
                if(is_null($ideRegion) || $ideRegion!=$proyectoRegion->ide_region){
                    return view ('home');
                }else{
                    $administradorRegion=true;
                }
            }
            $bitacora=$this->bitacoraPorProyectoRegion($proyectoRegion->ide_proyecto_region);
            $mensajes=array();
            $usuarioPrimerMensaje=-1;
            $estadoBitacora=null;
            if(!is_null($bitacora)){
                $mensajes=PlnBitacoraMensaje::with('usuario')->where('ide_bitacora_proyecto_region','=',$bitacora->ide_bitacora_proyecto_region)->get();
                if(count($mensajes)>0){
                    $usuarioPrimerMensaje=$mensajes[0]->ide_usuario;
                }
               $estadoBitacora=  PlnBitacoraProyectoRegion::where('ide_bitacora_proyecto_region','=',$bitacora->ide_bitacora_proyecto_region)->pluck('estado')->first();
            }
             
            $nombreProyecto=PlnProyectoPlanificacion::where('ide_proyecto','=',$proyectoRegion->ide_proyecto_planificacion)->pluck('descripcion')->first();
            $nombreRegion=CfgRegion::where('ide_region','=',$proyectoRegion->ide_region)->pluck('nombre')->first();
            
            $myusuario=Auth::user();
            $emails=array();
            if(!is_null($bitacora)){
                $emails=  DB::select(HPMEConstants::PLN_USUARIOS_BITACORA_PLANIFICACION,array('ideBitacora'=>$bitacora->ide_bitacora_proyecto_region,'myUsuario'=>$myusuario->ide_usuario));
            }
            $cadenaCorreos='';
            $first=true;
            
            $emailTarget='';
            if($administradorRegion){      
                //Se busca el correo del coordinar de monitoreo
                $emailTarget=$this->emailPlanificacion();
            }else{
                //se coloca por defecto el correo del administrador de la region
                $emailTarget=$this->emailAdministradorRegion($proyectoRegion->ide_region); 
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
            return view('observaciones_planificacion',array('ideProyectoRegion'=>$id,'estado'=>$proyectoRegion->estado,'rol'=>$rol,'nombreProyecto'=>$nombreProyecto,'nombreRegion'=>$nombreRegion,'bitacora'=>$bitacora,'mensajes'=>$mensajes,'usuario'=>$usuarioPrimerMensaje,'estadoBitacora'=>$estadoBitacora,'correos'=>$cadenaCorreos,'apruebaPlanificacion'=>$apruebaPlanificacion));
        }
        return view('home');
    } 
    
    private function emailAdministradorRegion($ideRegion){
        $emails=DB::select(HPMEConstants::PLN_OBSERVACIONES_EMAIL_ADMINISTRADOR,array('ideRegion'=>$ideRegion));
        if(count($emails)>0){
            return $emails[0]->email;
        } 
        return '';
    }

    private function emailPlanificacion(){
        $email= CfgParametro::where('nombre','=', HPMEConstants::PARAM_EMAIL_PLANIFICACION)->pluck('valor')->first();
        return $email;
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
    
    private function apruebaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFICACION_APROBAR_PLANIFICACION, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    private function bitacoraPorProyectoRegion($ideProyectoRegion){
        $bitacoras=  PlnBitacoraProyectoRegion::where('ide_proyecto_region','=',$ideProyectoRegion)->get();
        if(count($bitacoras)>0){
            return $bitacoras[0];
        }
        return null;
    }
    
    public function addMessage(Request $request){
        $rol=  request()->session()->get('rol');
        $ingresaPlan=$this->ingresoPlanificacion();
        $consultaPlanificacion=$this->consultaPlanificacion();
        $apruebaPlanificacion=$this->apruebaPlanificacion();
        if($apruebaPlanificacion || $ingresaPlan || $consultaPlanificacion){
            $proyectoRegion=  PlnProyectoRegion::find($request->ide_proyecto_region);
            if(is_null($proyectoRegion)){
                return response()->json(array('error'=>'Solo pudo guardar el pensaje para el proyecto de la regi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            if($proyectoRegion->estado==HPMEConstants::APROBADO){
                return response()->json(array('error'=>'No se puede agregar nuevos mensajes a una planificaci&oacute;n aprobada.'), HPMEConstants::HTTP_AJAX_ERROR);
            }
            
            $bitacora=$this->bitacoraPorProyectoRegion($request->ide_proyecto_region);
            $cambioEstado=  HPMEConstants::NO;
            if(is_null($bitacora)){
                $bitacora=new PlnBitacoraProyectoRegion();
                $bitacora->ide_proyecto_region=$request->ide_proyecto_region;
                $bitacora->estado=HPMEConstants::ABIERTO;
                $bitacora->save();
                $cambioEstado=  HPMEConstants::SI;
            }
            $user=Auth::user();
            $bitacoraMensaje=new PlnBitacoraMensaje();
            $bitacoraMensaje->ide_usuario=$user->ide_usuario;
            date_default_timezone_set(HPMEConstants::TIME_ZONE);
            $bitacoraMensaje->fecha=date(HPMEConstants::DATETIME_FORMAT,  time());
            $bitacoraMensaje->ide_bitacora_proyecto_region=$bitacora->ide_bitacora_proyecto_region;
            $bitacoraMensaje->mensaje=$request->mensaje;
            $bitacoraMensaje->save();      
            if($apruebaPlanificacion){
                //$proyectoRegion=  PlnProyectoRegion::find($request->ide_proyecto_region);
                if($proyectoRegion->estado==HPMEConstants::ENVIADO){
                    $proyectoRegion->estado=HPMEConstants::ABIERTO;
                    $proyectoRegion->save();
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
        }else{
            return response()->json(array('error'=>'Su usuario no tiene permisos para agregar mensajes a la bit&aacute;cora.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
    }
    
    private function enviarNotificacion($asunto,$para,$mensaje){
        $emails=  explode(",", $para);
        if(strlen($para)>0 && !is_null($emails) && count($emails)>0){
            $user=Auth::user();
            Mail::send('emails.reminder', ['title' => 'Observaci&oacute;n', 'content' => $mensaje], function ($message) use ($user,$asunto,$emails)
            {
                $message->from(env('MAIL_USERNAME'), $user->nombres.' '.$user->apellidos);

                $message->to($emails);
                
                $message->subject($asunto);

            });
        }     
    }
    
    public function marcarBitacora(Request $request){
        $bitacora=$this->bitacoraPorProyectoRegion($request->ide_proyecto_region);
        if(is_null($bitacora)){
            return response()->json(array('error'=>'No se entraron observaciones para la regi&oacute;n.'), HPMEConstants::HTTP_AJAX_ERROR);
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
}
