<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\PlnPresupuestoColaborador;
use App\PlnPresupuestoDepartamento;
use App\CfgDepartamento;
use App\PrivilegiosConstants;
use App\PlnProyectoPresupuesto;
use App\HPMEConstants;
use App\ExportConstants;

class PresupuestoConsolidado extends Controller
{
    //Obtiene usuarios y crea vista
    public function consolidadoColaborador($idePresupuestoColaborador){
        $presupuestoColaborador=  PlnPresupuestoColaborador::find($idePresupuestoColaborador);
        $idePresupuestoDepartamento=$presupuestoColaborador->ide_presupuesto_departamento;
        $ideDepartamento=  PlnPresupuestoDepartamento::where(array('ide_presupuesto_departamento'=>$idePresupuestoDepartamento))->pluck('ide_departamento')->first();
        $rol=  request()->session()->get('rol');
        if(!$this->departamentoDirector($ideDepartamento)){
            if(!$this->vistaPrivilegio($ideDepartamento)){
                return view('home');
            } 
        }
        $presupuestoColaborador->colaborador;
        $nombreColaborador='';
        if($presupuestoColaborador->colaborador->tipo==HPMEConstants::COLABORADOR){
            $nombreColaborador=$presupuestoColaborador->colaborador->nombres.' '.$presupuestoColaborador->colaborador->apellidos;
        }else{
            $nombreColaborador=$presupuestoColaborador->colaborador->nombres;
        }
        
        $consolidado=$this->buildConsolidado(array('idePresupuestoColaborador'=>$idePresupuestoColaborador),FALSE);
        return view('presupuesto_consolidado',array('cuentas'=>$consolidado,'idePresupuestoColaborador'=>$idePresupuestoColaborador,'nombre'=>$nombreColaborador,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento));
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
    
    public function exportConsolidadoDepartamento($idePresupuestoDepartamento){
        $presupuestoDepartamento=PlnPresupuestoDepartamento::find($idePresupuestoDepartamento);
        if(!$this->departamentoDirector($presupuestoDepartamento->ide_departamento)){
            if(!$this->vistaPrivilegio($presupuestoDepartamento->ide_departamento)){
                return view('home');
            } 
        }
        $presupuestoDepartamento->departamento;
        $nombreDepartamento=$presupuestoDepartamento->departamento->nombre;
        $consolidado=$this->buildConsolidado(array('idePresupuestoDepartamento'=>$idePresupuestoDepartamento),FALSE); 
        Excel::create("Presupuesto $nombreDepartamento", function($excel) use($consolidado) {
            $excel->sheet('Presupuesto', function($sheet) use ($consolidado){
                $sheet->loadView('presupuesto_depto_export', array('cuentas' => $consolidado));
                //$sheet->freezeFirstRow();
                //$sheet->freezeFirstRowAndColumn();
                $sheet->setFreeze('D2');
            });
        })->export('xls');

        //return view('presupuesto_depto_export',array('cuentas'=>$consolidado));        
    }
    
    public function exportPresupuesto($ideProyectoPresupuesto){
        $proyecto=  PlnProyectoPresupuesto::find($ideProyectoPresupuesto); 
        $date =  date_create($proyecto->fecha_proyecto);
        $year=$date->format("Y");
        $cuentas=DB::select(ExportConstants::PLN_PRESUPUESTO_EXPORT_SUN,array('ideProyectoPresupuesto'=>$ideProyectoPresupuesto));
        //return view('presupuesto_export',array('year'=>$year,'cuentas'=>$cuentas));
        Excel::create("Presupuesto ".$proyecto->descripcion, function($excel) use ($cuentas,$year) {
            $excel->sheet('Presupuesto', function($sheet) use ($cuentas,$year){
                $sheet->loadView('presupuesto_export',array('year'=>$year,'cuentas'=>$cuentas));
                //$sheet->freezeFirstRow();
                //$sheet->freezeFirstRowAndColumn();
                //$sheet->setFreeze('D2');
            });
        })->export('xls');
    }
    
    private function vistaPrivilegio($ideDepartamento){
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
            }
        }      
        return FALSE;
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
    
    public function consolidadoTrimestralColaborador($idePresupuestoColaborador){
        $presupuestoColaborador=  PlnPresupuestoColaborador::find($idePresupuestoColaborador);
        $idePresupuestoDepartamento=$presupuestoColaborador->ide_presupuesto_departamento;
        $ideDepartamento=  PlnPresupuestoDepartamento::where(array('ide_presupuesto_departamento'=>$idePresupuestoDepartamento))->pluck('ide_departamento')->first();
        $rol=  request()->session()->get('rol');
        if(!$this->departamentoDirector($ideDepartamento)){
            if(!$this->vistaPrivilegio($ideDepartamento)){
                return view('home');
            } 
        }
        $presupuestoColaborador->colaborador;
        $nombreColaborador='';
        if($presupuestoColaborador->colaborador->tipo==HPMEConstants::COLABORADOR){
            $nombreColaborador=$presupuestoColaborador->colaborador->nombres.' '.$presupuestoColaborador->colaborador->apellidos;
        }else{
            $nombreColaborador=$presupuestoColaborador->colaborador->nombres;
        }     
        $consolidado=$this->buildConsolidado(array('idePresupuestoColaborador'=>$idePresupuestoColaborador),TRUE);
        return view('presupuesto_consolidado_trimestral',array('cuentas'=>$consolidado,'idePresupuestoColaborador'=>$idePresupuestoColaborador,'nombre'=>$nombreColaborador,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento));
    }
    
    public function consolidadoDepartamento($idePresupuestoDepartamento){
        $rol=  request()->session()->get('rol');
        $presupuestoDepartamento=PlnPresupuestoDepartamento::find($idePresupuestoDepartamento);
        $vistaPrivilegio=$this->vistaPrivilegio($presupuestoDepartamento->ide_departamento);
        if(!$this->departamentoDirector($presupuestoDepartamento->ide_departamento)){
            if(!$vistaPrivilegio){
                return view('home');
            } 
        }
        $aprueba=$this->apruebaPrivilegio();
        $presupuestoDepartamento->departamento;
        $nombreDepartamento=$presupuestoDepartamento->departamento->nombre;        
        $consolidado=$this->buildConsolidado(array('idePresupuestoDepartamento'=>$idePresupuestoDepartamento),FALSE);        
        return view('presupuesto_cons_departamento',array('cuentas'=>$consolidado,'nombre'=>$nombreDepartamento,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento,'rol'=>$rol,'estado'=>$presupuestoDepartamento->estado,'aprueba'=>$aprueba,'vistaGeneral'=>$vistaPrivilegio));
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
    
    public function consolidadoTrimestralDepartamento($idePresupuestoDepartamento){
        $rol=  request()->session()->get('rol');
        $presupuestoDepartamento=PlnPresupuestoDepartamento::find($idePresupuestoDepartamento);
        if(!$this->departamentoDirector($presupuestoDepartamento->ide_departamento)){
            if(!$this->vistaPrivilegio($presupuestoDepartamento->ide_departamento)){
                return view('home');
            } 
        }
        $presupuestoDepartamento->departamento;
        $nombreDepartamento=$presupuestoDepartamento->departamento->nombre;
        $consolidado=$this->buildConsolidado(array('idePresupuestoDepartamento'=>$idePresupuestoDepartamento),TRUE);
        return view('presupuesto_cons_departamento_trim',array('cuentas'=>$consolidado,'nombre'=>$nombreDepartamento,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento,'estado'=>$presupuestoDepartamento->estado));
    }
    
    public function buildConsolidado($params,$trimestral){
        //Log::info("**************** MEME");
        //Log::info(memory_get_usage(true) );
        //Cuentas padre originales no se cuentan los nodos que son raíz
        $query=null;
        $queryRaiz=null;
        if(isset($params['idePresupuestoColaborador'])){
            $query=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_PADRE_COLABORADOR;
            $queryRaiz=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_RAIZ_COLABORADOR;
        }else{
            $query=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_PADRE_DEPARTAMENTO;
            $queryRaiz=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_RAIZ_DEPARTAMENTO;
        }
        $cuentasPadre=array();
        $cuentasPadreIngresadas=DB::select($query,$params);
        //Log::info($cuentasPadreIngresadas);
        //Cuentas padre originales se deben consolidar
        $cuentasConsolidar=array();
        
        foreach($cuentasPadreIngresadas as $cuenta){
            if(!is_null($cuenta->ide_cuenta_padre)){
                $cuentasConsolidar[]=$cuenta->ide_cuenta_padre;
                $padres=DB::select(HPMEConstants::CFG_CUENTAS_PARENT_SOLO_ID,array("ideCuenta"=>$cuenta->ide_cuenta_padre));
                foreach ($padres as $padre){
                    if(!in_array($padre->ide_cuenta, $cuentasPadre)){
                        $cuentasPadre[]=$padre->ide_cuenta;
                    }
                }
            }
        }
        //Log::info('*******CUENTAS PADRE********');
        //Log::info($cuentasPadre);     
        
        $cuentasRaiz=DB::select($queryRaiz,$params);
        //Se agrega los nodos raiz.
        foreach($cuentasRaiz as $raiz){
            if(!in_array($raiz->ide_cuenta, $cuentasPadre)){
                $cuentasPadre[]=$raiz->ide_cuenta;
            }
        } 
        
        return $this->buildConsolidadoInicial($cuentasPadre, $cuentasConsolidar, $params,$trimestral);
        //Log::info('*******CUENTAS PADRE ACTUALIZADAS ********');
       //$this->cuentas(null, $cuentasPadre,$cuentasConsolidar, $idePresupuestoColaborador);
        //Log::info("****************** FIN ******************");
       // Log::info('********************END MEM');
       // Log::info(memory_get_usage(true) );
    }
    
    
//    public function cuentas($ideCuentaPadre,$padres,$cuentasConsolidar,$idePresupuestoColaborador){
//        $query='';
//        $params=array();
//        if(is_null($ideCuentaPadre)){
//            $query=HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA_RAIZ;
//        }else{
//            $query=HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA;
//            $params=array('ideCuentaPadre'=>$ideCuentaPadre);
//        }
//        Log::info("****************t1");
//        $cuentasHijas=DB::select($query,$params);
//        Log::info($cuentasHijas);
//        foreach ($cuentasHijas as $hija){
//            if(in_array($hija->ide_cuenta, $padres)){
//                $resultHijas=$this->cuentas($hija->ide_cuenta, $padres,$idePresupuestoColaborador);
//                if(is_null($resultHijas)){
//                    //Consolidar
//                    return $this->consolidarCuenta($hija, $idePresupuestoColaborador);
//                }
//            }else{
//                Log::info("****** CUENTA NO EN PADRES**********");
//                Log::info($hija->nombre);
//            }
//        }        
//        return null;        
//    }
       
    public function buildConsolidadoInicial($cuentasPadre,$cuentasConsolidar,$parameterQuery,$trimestral){     
        //Log::info("**** CONSOLIDADO INICIAL ******");
        $cuentasIniciales=DB::select($query=HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA_RAIZ);
        //Log::info($cuentasIniciales);
        $result=array();
        foreach ($cuentasIniciales as $root){
             $result_cuenta=$this->buildReporteCuenta($root, $cuentasPadre, $cuentasConsolidar, $parameterQuery,$trimestral);
             if(!is_null($result_cuenta)){
                 $totalCuentas=count($result_cuenta);
                 if($totalCuentas>0){
                     $totalCuentaRaiz=$result_cuenta[0]['total'];
                     if($totalCuentaRaiz>0){
                         for($i=0;$i<$totalCuentas;$i++){
                             $result_cuenta[$i]['porc']=($result_cuenta[$i]['total']/$totalCuentaRaiz)*100;      
                         }                         
                     }
                 }
                $result=array_merge($result, $result_cuenta);
             }
        }        
        return $result;
    }
    
    public function buildReporteCuenta($cuenta,$cuentasPadre,$cuentasConsolidar,$parameterQuery,$trimestral,$nivel=0){
        if(in_array($cuenta->ide_cuenta,$cuentasConsolidar)){
            //Log::info("Cuenta $cuenta->nombre en consolidar.");
            return $this->consolidarCuenta($cuenta, $parameterQuery,$nivel,$trimestral);
        }else{
            if(in_array($cuenta->ide_cuenta, $cuentasPadre)){
                //Log::info("Cuenta $cuenta->nombre en cuentas padre.");
                $cuentaHijas=DB::select(HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA,array('ideCuentaPadre'=>$cuenta->ide_cuenta));
                    $result=array();
                    $itemCuenta['cuenta']=$cuenta->cuenta;
                    $itemCuenta['nombre']=$cuenta->nombre;
                    $result[]=$itemCuenta; 
                    foreach($cuentaHijas as $hija){
                        //Log::info('buscando hija..... '.$hija->nombre);
                        $result_hija=$this->buildReporteCuenta($hija, $cuentasPadre, $cuentasConsolidar, $parameterQuery,$trimestral,$nivel+1);
                        //Log::info("*****result hija ");
                        //Log::info($result_hija);
                        if(!is_null($result_hija)){
                            //sumariazar
                            $itemCuenta=$this->totalizar($itemCuenta, $result_hija[0],$trimestral);
                            $result=array_merge($result,$result_hija);
                            //Log::info("*****ACTUALIZANDO HIJAS******");
                            //Log::info($result_hija);
                            //Log::info("********************FIN HIJAS***********");
                        }
                    }
                    //Log::info("*********** print array "); 
                    //Log::info($itemCuenta);
                    $itemCuenta['nivel']=$nivel;
                    $result[0]=$itemCuenta;
                    //Log::info($result);                 
                    return $result;
            }
        }       
        return null;
    }
    
    
    private function totalizar($itemCuenta,$itemTotal,$trimestral){
        $items=12;
        if($trimestral){
            $items=4;
        }
        for($i=1;$i<=$items;$i++){
            if(isset($itemTotal['item'.$i])){
                if(isset($itemCuenta['item'.$i])){
                    $itemCuenta['item'.$i]=$itemCuenta['item'.$i]+$itemTotal['item'.$i];
                    $itemCuenta['ej'.$i]=$itemCuenta['ej'.$i]+$itemTotal['ej'.$i];
                }else{
                    $itemCuenta['item'.$i]=$itemTotal['item'.$i];
                    $itemCuenta['ej'.$i]=$itemTotal['ej'.$i];
                }
            }
        } 
        if(isset($itemTotal['total'])){
            if(isset($itemCuenta['total'])){
                $itemCuenta['total']=$itemCuenta['total']+$itemTotal['total'];
                $itemCuenta['ejtotal']=$itemCuenta['ejtotal']+$itemTotal['ejtotal'];
            }else{
                $itemCuenta['total']=$itemTotal['total'];
                $itemCuenta['ejtotal']=$itemTotal['ejtotal'];
            }
        }
        //Log::info("***********ITEM CUENTA********************");
        //Log::info($itemCuenta);
        return $itemCuenta;
    }


    public function consolidarCuenta($cuenta,$parameterQuery,$nivel,$trimestral){
        $cuentasHijas=null;
        if(isset($parameterQuery['idePresupuestoColaborador'])){
            $cuentasHijas=DB::select(HPMEConstants::CONSOLIDADO_COLABORADOR_CUENTA_PADRE,array('idePresupuestoColaborador'=>$parameterQuery['idePresupuestoColaborador'],'ideCuentaPadre'=>$cuenta->ide_cuenta));  
        }else{
            if(isset($parameterQuery['idePresupuestoDepartamento'])){
                $cuentasHijas=DB::select(HPMEConstants::CONSOLIDADO_DEPARTAMENTO_CUENTA_PADRE,array('idePresupuestoDepartamento'=>$parameterQuery['idePresupuestoDepartamento'],'ideCuentaPadre'=>$cuenta->ide_cuenta));  
            }
        }
        if($trimestral){
            return $this->trimestral($cuenta,$cuentasHijas, $nivel);
        }else{          
            return $this->mensual($cuenta,$cuentasHijas, $nivel);
        }      
    }
    
    private function mensual($cuenta,$cuentasHijas,$nivel){
        $result=array();
        $item1=0.0;
        $item2=0.0;
        $item3=0.0;
        $item4=0.0;
        $item5=0.0;
        $item6=0.0;
        $item7=0.0;
        $item8=0.0;
        $item9=0.0;
        $item10=0.0;
        $item11=0.0;
        $item12=0.0;
        $total=0.0;
        $totalCuenta=0.0;
        
        $ej1=0.0;
        $ej2=0.0;
        $ej3=0.0;
        $ej4=0.0;
        $ej5=0.0;
        $ej6=0.0;
        $ej7=0.0;
        $ej8=0.0;
        $ej9=0.0;
        $ej10=0.0;
        $ej11=0.0;
        $ej12=0.0;
        $ejtotal=0.0;
        $ejtotalCuenta=0.0;
        
        foreach($cuentasHijas as $hija){
            $item=array();
            $item['cuenta']=$hija->cuenta;
            $item['nombre']=$hija->nombre;
            $totalCuenta=0.0;
            $ejtotalCuenta=0.0;
            
            if($hija->item1>0){
                $item['item1']=$hija->item1;
                $item['ej1']=$hija->ej1;
                $item1+=$hija->item1;
                $ej1+=$hija->ej1;
                $totalCuenta+=$hija->item1;
                $ejtotalCuenta+=$hija->ej1;
            }
            if($hija->item2>0){
                $item['item2']=$hija->item2;
                $item['ej2']=$hija->ej2;
                $item2+=$hija->item2;
                $ej2+=$hija->ej2;
                $totalCuenta+=$hija->item2;
                $ejtotalCuenta+=$hija->ej2;
            }
            if($hija->item3>0){
                $item['item3']=$hija->item3;
                $item['ej3']=$hija->ej3;
                $item3+=$hija->item3;
                $ej3+=$hija->ej3;
                $totalCuenta+=$hija->item3;
                $ejtotalCuenta+=$hija->ej3;
            }
            if($hija->item4>0){
                $item['item4']=$hija->item4;
                $item['ej4']=$hija->ej4;
                $item4+=$hija->item4;
                $ej4+=$hija->ej4;
                $totalCuenta+=$hija->item4;
                $ejtotalCuenta+=$hija->ej4;
            }
            if($hija->item5>0){
                $item['item5']=$hija->item5;
                $item['ej5']=$hija->ej5;
                $item5+=$hija->item5;
                $ej5+=$hija->ej5;
                $totalCuenta+=$hija->item5;
                $ejtotalCuenta+=$hija->ej5;
            }
            if($hija->item6>0){
                $item['item6']=$hija->item6;
                $item['ej6']=$hija->ej6;
                $item6+=$hija->item6;
                $ej6+=$hija->ej6;
                $totalCuenta+=$hija->item6;
                $ejtotalCuenta+=$hija->ej6;
            }
            if($hija->item7>0){
                $item['item7']=$hija->item7;
                $item['ej7']=$hija->ej7;
                $item7+=$hija->item7;
                $ej7+=$hija->ej7;
                $totalCuenta+=$hija->item7;
                $ejtotalCuenta+=$hija->ej7;
            }
            if($hija->item8>0){
                $item['item8']=$hija->item8;
                $item['ej8']=$hija->ej8;
                $item8+=$hija->item8;
                $ej8+=$hija->ej8;
                $totalCuenta+=$hija->item8;
                $ejtotalCuenta+=$hija->ej8;
            }
            if($hija->item9>0){
                $item['item9']=$hija->item9;
                $item['ej9']=$hija->ej9;
                $item9+=$hija->item9;
                $ej9+=$hija->ej9;
                $totalCuenta+=$hija->item9;
                $ejtotalCuenta+=$hija->ej9;
            }
            if($hija->item10>0){
                $item['item10']=$hija->item10;
                $item['ej10']=$hija->ej10;
                $item10+=$hija->item10;
                $ej10+=$hija->ej10;
                $totalCuenta+=$hija->item10;
                $ejtotalCuenta+=$hija->ej10;
            }
            if($hija->item11>0){
                $item['item11']=$hija->item11;
                $item['ej11']=$hija->ej11;
                $item11+=$hija->item11;
                $ej11+=$hija->ej11;
                $totalCuenta+=$hija->item11;
                $ejtotalCuenta+=$hija->ej11;
            }
            if($hija->item12>0){
                $item['item12']=$hija->item12;
                $item['ej12']=$hija->ej12;
                $item12+=$hija->item12;
                $ej12+=$hija->ej12;
                $totalCuenta+=$hija->item12;
                $ejtotalCuenta+=$hija->ej12;
            }
            $item['total']=$totalCuenta;
            $item['ejtotal']=$ejtotalCuenta;
            $item['nivel']=$nivel+1;
            $result[]=$item;
        } 
        //$itemCuenta=array();
        $itemCuenta['cuenta']=$cuenta->cuenta;
        $itemCuenta['nombre']=$cuenta->nombre;
        if($item1>0){
            $itemCuenta['item1']=$item1;
            $itemCuenta['ej1']=$ej1;
            $ejtotal+=$ej1;
            $total+=$item1;
        }
        if($item2>0){
            $itemCuenta['item2']=$item2;
            $itemCuenta['ej2']=$ej2;
            $ejtotal+=$ej2;
            $total+=$item2;
        }
        if($item3>0){
            $itemCuenta['item3']=$item3;
            $itemCuenta['ej3']=$ej3;
            $ejtotal+=$ej3;
            $total+=$item3;
        }
        if($item4>0){
            $itemCuenta['item4']=$item4;
            $itemCuenta['ej4']=$ej4;
            $ejtotal+=$ej4;
            $total+=$item4;
        }
        if($item5>0){
            $itemCuenta['item5']=$item5;
            $itemCuenta['ej5']=$ej5;
            $ejtotal+=$ej5;
            $total+=$item5;
        }
        if($item6>0){
            $itemCuenta['item6']=$item6;
            $itemCuenta['ej6']=$ej6;
            $ejtotal+=$ej6;
            $total+=$item6;
        }
        if($item7>0){
            $itemCuenta['item7']=$item7;
            $itemCuenta['ej7']=$ej7;
            $ejtotal+=$ej7;
            $total+=$item7;
        }
        if($item8>0){
            $itemCuenta['item8']=$item8;
            $itemCuenta['ej8']=$ej8;
            $ejtotal+=$ej8;
            $total+=$item8;
        }
        if($item9>0){
            $itemCuenta['item9']=$item9;
            $itemCuenta['ej9']=$ej9;
            $ejtotal+=$ej9;
            $total+=$item9;
        }
        if($item10>0){
            $itemCuenta['item10']=$item10;
            $itemCuenta['ej10']=$ej10;
            $ejtotal+=$ej10;
            $total+=$item10;
        }
        if($item11>0){
            $itemCuenta['item11']=$item11;
            $itemCuenta['ej11']=$ej11;
            $ejtotal+=$ej11;
            $total+=$item11;
        }
        if($item12>0){
            $itemCuenta['item12']=$item12;
            $itemCuenta['ej12']=$ej12;
            $ejtotal+=$ej12;
            $total+=$item12;
        }
        $itemCuenta['total']=$total;
        $itemCuenta['ejtotal']=$ejtotal;
        $itemCuenta['nivel']=$nivel;
        //$result[]=$itemCuenta;
        array_unshift($result,$itemCuenta);
        return $result;     
    }
    
    
    private function trimestral($cuenta,$cuentasHijas,$nivel){
        $result=array();
        $item1=0.0;
        $item2=0.0;
        $item3=0.0;
        $item4=0.0;
        
        $itemej1=0.0;
        $itemej2=0.0;
        $itemej3=0.0;
        $itemej4=0.0;
        
        $total=0.0;
        $ejtotal=0.0;
        $totalCuenta=0.0;
        $totalCuejtaEj=0.0;
        foreach($cuentasHijas as $hija){
            $item=array();
            $item['cuenta']=$hija->cuenta;
            $item['nombre']=$hija->nombre;
            $trim1=0.0;
            $trim2=0.0;
            $trim3=0.0;
            $trim4=0.0;
            
            $ej1=0.0;
            $ej2=0.0;
            $ej3=0.0;
            $ej4=0.0;
        
            $totalCuenta=0.0;
            if($hija->item1>0){
                $trim1+=$hija->item1;
                $ej1+=$hija->ej1;
            }
            
            if($hija->item2>0){
                $trim1+=$hija->item2;
                $ej1+=$hija->ej2;
            }
            if($hija->item3>0){
                $trim1+=$hija->item3;
                $ej1+=$hija->ej3;
            }
            $item['item1']=$trim1;
            $item['ej1']=$ej1;
            $totalCuenta+=$trim1;
            $totalCuejtaEj+=$ej1;
            
            if($hija->item4>0){
                $trim2+=$hija->item4;
                $ej2+=$hija->ej4;
            }
            if($hija->item5>0){
                $trim2+=$hija->item5;
                $ej2+=$hija->ej5;
            }
            if($hija->item6>0){
                $trim2+=$hija->item6;
                $ej2+=$hija->ej6;
            }
            $item['item2']=$trim2;
            $item['ej2']=$ej2;
            $totalCuenta+=$trim2;
            $totalCuejtaEj+=$ej2;
            
            if($hija->item7>0){
                $trim3+=$hija->item7;
                $ej3+=$hija->ej7;
            }
            if($hija->item8>0){
                $trim3+=$hija->item8;
                $ej3+=$hija->ej8;
            }
            if($hija->item9>0){
                $trim3+=$hija->item9;
                $ej3+=$hija->ej9;
            }
            $item['item3']=$trim3;
            $item['ej3']=$ej3;
            $totalCuenta+=$trim3;
            $totalCuejtaEj+=$ej3;
            
            if($hija->item10>0){
                $trim4+=$hija->item10;
                $ej4+=$hija->ej10;
            }
            if($hija->item11>0){
                $trim4+=$hija->item11;
                $ej4+=$hija->ej11;
            }   
            if($hija->item12>0){
                $trim4+=$hija->item12;
                $ej4+=$hija->ej12;
            }
            $item['item4']=$trim4;
            $item['ej4']=$ej4;
            $totalCuenta+=$trim4;
            $totalCuejtaEj+=$ej4;
            
            $item1+=$trim1;
            $item2+=$trim2;
            $item3+=$trim3;
            $item4+=$trim4;
            
            $itemej1+=$ej1;
            $itemej1+=$ej2;
            $itemej1+=$ej3;
            $itemej1+=$ej4;
            
            $item['total']=$totalCuenta;
            $item['ejtotal']=$totalCuejtaEj;
            $item['nivel']=$nivel+1;
            $result[]=$item;
        } 
        $itemCuenta=array();
        $itemCuenta['cuenta']=$cuenta->cuenta;
        $itemCuenta['nombre']=$cuenta->nombre;
        if($item1>0){
            $itemCuenta['item1']=$item1;
            $itemCuenta['ej1']=$itemej1;
            $total+=$item1;
            $ejtotal+=$itemej1;
        }
        if($item2>0){
            $itemCuenta['item2']=$item2;
            $itemCuenta['ej2']=$itemej2;
            $ejtotal+=$itemej2;
            $total+=$item2;
        }
        if($item3>0){
            $itemCuenta['item3']=$item3;
            $itemCuenta['ej3']=$itemej3;
            $ejtotal+=$itemej3;
            $total+=$item3;
        }
        if($item4>0){
            $itemCuenta['item4']=$item4;
            $itemCuenta['ej4']=$itemej4;
            $ejtotal+=$itemej4;
            $total+=$item4;
        }
        $itemCuenta['total']=$total;
        $itemCuenta['ejtotal']=$ejtotal;
        $itemCuenta['nivel']=$nivel;
        //$result[]=$itemCuenta;
        array_unshift($result,$itemCuenta);
        return $result;     
    }
    
    public function ejecucionPresupuestoDepartamento($idePresupuestoDepartamento){
        $rol=  request()->session()->get('rol');
        $presupuestoDepartamento=PlnPresupuestoDepartamento::find($idePresupuestoDepartamento);
        $vistaPrivilegio=$this->vistaPrivilegio($presupuestoDepartamento->ide_departamento);
        if(!$this->departamentoDirector($presupuestoDepartamento->ide_departamento)){
            if(!$vistaPrivilegio){
                return view('home');
            } 
        }
        $aprueba=$this->apruebaPrivilegio();
        $presupuestoDepartamento->departamento;
        $nombreDepartamento=$presupuestoDepartamento->departamento->nombre;        
        $consolidado=$this->buildConsolidado(array('idePresupuestoDepartamento'=>$idePresupuestoDepartamento),FALSE);        
        return view('mon_presupuesto_ejecucion_dep',array('cuentas'=>$consolidado,'nombre'=>$nombreDepartamento,'idePresupuestoDepartamento'=>$idePresupuestoDepartamento,'rol'=>$rol,'estado'=>$presupuestoDepartamento->estado,'aprueba'=>$aprueba,'vistaGeneral'=>$vistaPrivilegio));        
    }
    
    
    
}