<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HPMEConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PresupuestoConsolidado extends Controller
{
    //Obtiene usuarios y crea vista
    public function consolidadoColaborador($idePresupuestoColaborador){
        $this->buildConsolidado($idePresupuestoColaborador);
        return view('presupuesto_consolidado',array('cuentas'=>array()));
    }
    
    public function buildConsolidado($idePresupuestoColaborador){
        Log::info('consolidado '.$idePresupuestoColaborador);
        $query=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_PADRE_COLABORADOR;
        $cuentasPadre=array();
        $cuentasPadreIngresadas=DB::select($query,array('idePresupuestoColaborador'=>$idePresupuestoColaborador));
        Log::info($cuentasPadreIngresadas);
        foreach($cuentasPadreIngresadas as $cuenta){
            if(!is_null($cuenta->ide_cuenta_padre)){
                $cuentasPadre[]=$cuenta->ide_cuenta_padre;
                $padres=DB::select(HPMEConstants::CFG_CUENTAS_PARENT_SOLO_ID,array("ideCuenta"=>$cuenta->ide_cuenta_padre));
                foreach ($padres as $padre){
                    if(!in_array($padre->ide_cuenta, $cuentasPadre)){
                        $cuentasPadre[]=$padre->ide_cuenta;
                    }
                }
            }
        }
        Log::info('*******CUENTAS PADRE********');
        Log::info($cuentasPadre);     
        $queryRaiz=HPMEConstants::PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_RAIZ_COLABORADOR;
        $cuentasRaiz=DB::select($queryRaiz,array('idePresupuestoColaborador'=>$idePresupuestoColaborador));
        //Se agrega los nodos raiz.
        foreach($cuentasRaiz as $raiz){
            if(!in_array($raiz->ide_cuenta, $cuentasPadre)){
                $cuentasPadre[]=$raiz->ide_cuenta;
            }
        } 
        Log::info('*******CUENTAS PADRE ACTUALIZADAS ********');
        $this->cuentas(null, $cuentasPadre, $idePresupuestoColaborador);
        Log::info("****************** FIN ******************");
    }
    
    
    public function cuentas($ideCuentaPadre,$padres,$idePresupuestoColaborador){
        $query='';
        $params=array();
        if(is_null($ideCuentaPadre)){
            $query=HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA_RAIZ;
        }else{
            $query=HPMEConstants::PLN_CUENTAS_HIJAS_CONSOLIDA;
            $params=array('ideCuentaPadre'=>$ideCuentaPadre);
        }
        Log::info("****************t1");
        $cuentasHijas=DB::select($query,$params);
        Log::info($cuentasHijas);
        foreach ($cuentasHijas as $hija){
            if(in_array($hija->ide_cuenta, $padres)){
                $resultHijas=$this->cuentas($hija->ide_cuenta, $padres,$idePresupuestoColaborador);
                if(is_null($resultHijas)){
                    //Consolidar
                    return $this->consolidarCuenta($hija, $idePresupuestoColaborador);
                }
            }else{
                Log::info("****** CUENTA NO EN PADRES**********");
                Log::info($hija->nombre);
            }
        }        
        return null;        
    }
    
    public function consolidarCuenta($cuenta,$idePresupuestoColaborador){
        $result=array();
        $cuentasHijas=DB::select(HPMEConstants::CONSOLIDADO_COLABORADOR_CUENTA_PADRE,array('idePresupuestoColaborador'=>$idePresupuestoColaborador,'ideCuentaPadre'=>$cuenta->ide_cuenta));
        Log::info($cuentasHijas);
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
        foreach($cuentasHijas as $hija){
            $item['cuenta']=$hija->cuenta;
            $item['nombre']=$hija->nombre;
            if($hija->item1>0){
                $item['item1']=$hija->item1;
                $item1+=$hija->item1;
            }
            if($hija->item2>0){
                $item['item2']=$hija->item2;
                $item2+=$hija->item2;
            }
            if($hija->item3>0){
                $item['item3']=$hija->item3;
                $item3+=$hija->item3;
            }
            if($hija->item4>0){
                $item['item4']=$hija->item4;
                $item4+=$hija->item4;
            }
            if($hija->item5>0){
                $item['item5']=$hija->item5;
                $item5+=$hija->item5;
            }
            if($hija->item6>0){
                $item['item6']=$hija->item6;
                $item6+=$hija->item6;
            }
            if($hija->item7>0){
                $item['item7']=$hija->item7;
                $item7+=$hija->item7;
            }
            if($hija->item8>0){
                $item['item8']=$hija->item8;
                $item8+=$hija->item8;
            }
            if($hija->item9>0){
                $item['item9']=$hija->item9;
                $item9+=$hija->item9;
            }
            if($hija->item10>0){
                $item['item10']=$hija->item10;
                $item10+=$hija->item10;
            }
            if($hija->item11>0){
                $item['item11']=$hija->item11;
                $item11+=$hija->item11;
            }
            if($hija->item12>0){
                $item['item12']=$hija->item12;
                $item12+=$hija->item12;
            }
            $result[]=$item;
        } 
        //$itemCuenta=array();
        $itemCuenta['cuenta']=$cuenta->cuenta;
        $itemCuenta['nombre']=$cuenta->nombre;
        if($item1>0){
            $itemCuenta['item1']=$item1;
        }
        if($item2>0){
            $itemCuenta['item2']=$item2;
        }
        if($item3>0){
            $itemCuenta['item3']=$item3;
        }
        if($item4>0){
            $itemCuenta['item4']=$item4;
        }
        if($item5>0){
            $itemCuenta['item5']=$item5;
        }
        if($item6>0){
            $itemCuenta['item6']=$item6;
        }
        if($item7>0){
            $itemCuenta['item7']=$item7;
        }
        if($item8>0){
            $itemCuenta['item8']=$item8;
        }
        if($item9>0){
            $itemCuenta['item9']=$item9;
        }
        if($item10>0){
            $itemCuenta['item10']=$item10;
        }
        if($item11>0){
            $itemCuenta['item11']=$item11;
        }
        if($item12>0){
            $itemCuenta['item12']=$item12;
        }
        //$result[]=$itemCuenta;
        array_unshift($result,$itemCuenta);
        Log::info($result);
        Log::info('********** FIN CONSOLIDAR ***************');
        return $result;
    } 
}