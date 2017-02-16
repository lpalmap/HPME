<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlnProyectoMeta;
use App\HPMEConstants;
use App\PlnObjetivoMeta;
use App\CfgObjetivo;
use App\PrivilegiosConstants;

class PlanificacionObjetivo extends Controller
{
    //Obtiene metas y crea vista
    
    public function objetivoMeta($ideProyectoMeta){
        $meta= PlnProyectoMeta::find($ideProyectoMeta);
        $meta->meta; 
        $ideProyecto=$meta->ide_proyecto;
        $objetivos= PlnObjetivoMeta::with("objetivo")->where("ide_proyecto_meta",$ideProyectoMeta)->get(); 
        $creaPlanificacion=$this->creaPlanificacion();
        return view('planificacionobjetivos',array('items'=>$objetivos,'meta'=>$meta->meta->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'creaPlanificacion'=>$creaPlanificacion));    
    }
    
    private function creaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFICACION_CREAR_PROYECTO, $privilegios)
                    ){
                return TRUE;
            }
        }      
        return FALSE;
    }
    
    public function deleteObjetivo($ideObjetivoMeta){
        $item = PlnObjetivoMeta::destroy($ideObjetivoMeta);
        return response()->json($item);
    }
    
    public function retriveAllObjetivos(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $objetivos=$this->objetivoPorMeta($ideProyecto);
        return response()->json($objetivos);
    }
    
    public function addObjetivo(Request $request){
        $listaItems=$request->items;
        $ideProyectoMeta=$request->ide_proyecto_meta;
        $proyectoMeta=  PlnProyectoMeta::find($ideProyectoMeta);
        $this->validateRequest($request, $proyectoMeta->ide_proyecto);
        $items=array();
        foreach($listaItems as $item){
            $nItem=new PlnObjetivoMeta();
            $nItem->ide_proyecto_meta=$ideProyectoMeta;
            $nItem->ide_proyecto=$proyectoMeta->ide_proyecto;
            $nItem->ide_meta=$proyectoMeta->ide_meta;
            $nItem->ide_objetivo=$item['ide_objetivo'];
            $nItem->save();
            $nItem->objetivo;
            $items[]=$nItem;
        }
        return response()->json($items);
    }
    
    public function objetivoPorMeta($ideProyecto){
        $objetivo=  new CfgObjetivo();
        $params = array("ideProyecto"=>$ideProyecto);
        return $objetivo->selectQuery(HPMEConstants::OBJETIVO_META_QUERY,$params);
    }
    
    public function validateRequest($request,$ide_proyecto){
        $rules=[
            'items.*.ide_objetivo' => 'unique:pln_objetivo_meta,ide_objetivo,NULL,ide_objetivo,ide_proyecto,'.$ide_proyecto,
        ];
        $messages=[
            'unique' => 'El objetivo ya fue agregado por otro usuario/sessi&oacute;n.'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}