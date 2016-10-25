<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HPMEConstants;
use App\PlnIndicadorArea;
use App\CfgIndicador;
use App\PlnAreaObjetivo;

class PlanificacionIndicador extends Controller
{
    
    public function indicadorPorArea($ideProyecto){
        $indicador=  new CfgIndicador();
        $params = array("ideProyecto"=>$ideProyecto);
        return $indicador->selectQuery(HPMEConstants::INDICADOR_AREA_QUERY,$params);
    }
    
    public function indicadorArea($ideAreaObjetivo){
        $area= PlnAreaObjetivo::find($ideAreaObjetivo);
        $area->area;
        $area->objetivoMeta;
        $ideProyecto=$area->ide_proyecto;
        $ideProyectoMeta=$area->objetivoMeta->ide_proyecto_meta;
        $ideObjetivoMeta=$area->ide_objetivo_meta;
        $indicadores=PlnIndicadorArea::with("indicador")->where("ide_area_objetivo",$ideAreaObjetivo)->get();
        $rol=  request()->session()->get('rol');
        return view('planificacionindicadores',array('items'=>$indicadores,'area'=>$area->area->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta,'ideAreaObjetivo'=>$ideAreaObjetivo,'rol'=>$rol));    
    }
    
    public function deleteIndicador($ideIndicadorArea){
        $item = PlnIndicadorArea::destroy($ideIndicadorArea);
        return response()->json($item);
    }
    
    public function retriveAllIndicadores(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $indicadores=$this->indicadorPorArea($ideProyecto);
        return response()->json($indicadores);
    }
    
    public function addIndicador(Request $request){
        $listaItems=$request->items;
        $ideAreaObjetivo=$request->ide_area_objetivo;
        $areaObjetivo= PlnAreaObjetivo::find($ideAreaObjetivo);
        $this->validateRequest($request, $areaObjetivo->ide_proyecto);
        $items=array();
        foreach($listaItems as $item){
            $nItem=new PlnIndicadorArea();
            $nItem->ide_area_objetivo=$ideAreaObjetivo;
            $nItem->ide_proyecto=$areaObjetivo->ide_proyecto;
            $nItem->ide_meta=$areaObjetivo->ide_meta;
            $nItem->ide_objetivo=$areaObjetivo->ide_objetivo;
            $nItem->ide_area=$areaObjetivo->ide_area;
            $nItem->ide_indicador=$item['ide_indicador'];
            $nItem->save();
            $nItem->indicador;
            $items[]=$nItem;
        }
        return response()->json($items);
    } 
    
    public function validateRequest($request,$ide_proyecto){
        $rules=[
            'items.*.ide_indicador' => 'unique:pln_indicador_area,ide_indicador,NULL,ide_indicador,ide_proyecto,'.$ide_proyecto,
        ];
        $messages=[
            'unique' => 'El indicador ya fue agregado por otro usuario/sessi&oacute;n.'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}