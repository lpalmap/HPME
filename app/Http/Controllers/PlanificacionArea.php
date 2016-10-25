<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HPMEConstants;
use App\PlnObjetivoMeta;
use App\PlnAreaObjetivo;
use App\CfgAreaAtencion;

class PlanificacionArea extends Controller
{
   public function areaObjetivo($ideObjetivoMeta){
        $objetivo= PlnObjetivoMeta::find($ideObjetivoMeta);
        $objetivo->objetivo; 
        $ideProyecto=$objetivo->ide_proyecto;
        $ideProyectoMeta=$objetivo->ide_proyecto_meta;     
        $areas= PlnAreaObjetivo::with("area")->where("ide_objetivo_meta",$ideObjetivoMeta)->get();
        $rol=  request()->session()->get('rol');
        return view('planificacionarea',array('items'=>$areas,'objetivo'=>$objetivo->objetivo->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta,'rol'=>$rol));    
    }
    
    public function areaPorObjetivo($ideProyecto){
        $area=  new CfgAreaAtencion();
        $params = array("ideProyecto"=>$ideProyecto);
        return $area->selectQuery(HPMEConstants::AREA_OBJETIVO_QUERY,$params);
    }
    
    public function deleteArea($ideAreaObjetivo){
        $item = PlnAreaObjetivo::destroy($ideAreaObjetivo);
        return response()->json($item);
    }
    
    public function retriveAllAreas(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $areas=$this->areaPorObjetivo($ideProyecto);
        return response()->json($areas);
    }
    
    public function addArea(Request $request){
        $listaItems=$request->items;
        $ideObjetivoMeta=$request->ide_objetivo_meta;
        $objetivoMeta= PlnObjetivoMeta::find($ideObjetivoMeta);
        $ideProyecto=$objetivoMeta->ide_proyecto;
        $this->validateRequest($request, $ideProyecto);
        $items=array();
        foreach($listaItems as $item){
            $nItem=new PlnAreaObjetivo();
            $nItem->ide_objetivo_meta=$ideObjetivoMeta;
            $nItem->ide_proyecto=$objetivoMeta->ide_proyecto;
            $nItem->ide_meta=$objetivoMeta->ide_meta;
            $nItem->ide_objetivo=$objetivoMeta->ide_objetivo;
            $nItem->ide_area=$item['ide_area'];
            $nItem->save();
            $nItem->area;
            $items[]=$nItem;
        }
        return response()->json($items);
    }    
    
    public function validateRequest($request,$ide_proyecto){
        $rules=[
            'items.*.ide_area' => 'unique:pln_area_objetivo,ide_area,NULL,ide_area,ide_proyecto,'.$ide_proyecto,
        ];
        $messages=[
            'unique' => 'El &Aacute;rea de Atenci&oacute;n ya fue agregada por otro usuario/sessi&oacute;n.'
        ];
        $this->validate($request, $rules,$messages);        
    }
}