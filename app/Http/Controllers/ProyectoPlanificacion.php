<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\CfgProducto;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;
use App\PlnProyectoMeta;
use App\PlnObjetivoMeta;
use App\CfgMeta;
use App\CfgObjetivo;
use App\HPMEConstants;
use App\PlnAreaObjetivo;
use App\CfgAreaAtencion;
use App\PlnIndicadorArea;
use App\CfgIndicador;
use App\PlnProductoIndicador;

class ProyectoPlanificacion extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $proyecto=new PlnProyectoPlanificacion();
        $data=$proyecto->all(); 
        $periodos=  CfgListaValor::all()->where('grupo_lista', 'PERIODO_PLANIFICACION');
        return view('planificacionanual',array('items'=>$data),array('periodos'=>$periodos));
    }
    
    public function metasProyecto($ideProyecto){
        Log::info("Buscando proyecto $ideProyecto");
        $proyecto=  PlnProyectoPlanificacion::findOrFail($ideProyecto);
        $metas = PlnProyectoMeta::with("meta")->where('ide_proyecto', $ideProyecto)->get();
        return view('planificacionmetas',array('items'=>$metas,'proyecto'=>$proyecto->descripcion,'ideProyecto'=>$ideProyecto));
    }
    
    public function metasPorProyecto($ideProyecto){
        $metas=  new CfgMeta();;
        $params = array("ideProyecto"=>$ideProyecto);
        return $metas->selectQuery(HPMEConstants::META_PROYECTO_QUERY,$params);
    }
    
    public function objetivoPorMeta($ideProyectoMeta){
        $objetivo=  new CfgObjetivo();
        $params = array("ideProyectoMeta"=>$ideProyectoMeta);
        return $objetivo->selectQuery(HPMEConstants::OBJETIVO_META_QUERY,$params);
    }
    
    public function areaPorObjetivo($ideObjetivoMeta){
        $area=  new CfgAreaAtencion();
        $params = array("ideObjetivoMeta"=>$ideObjetivoMeta);
        return $area->selectQuery(HPMEConstants::AREA_OBJETIVO_QUERY,$params);
    }
    
    public function indicadorPorArea($ideAreaObjetivo){
        $indicador=  new CfgIndicador();
        $params = array("ideAreaObjetivo"=>$ideAreaObjetivo);
        return $indicador->selectQuery(HPMEConstants::INDICADOR_AREA_QUERY,$params);
    }
    
    public function objetivoMeta($ideProyectoMeta){
        $meta= PlnProyectoMeta::find($ideProyectoMeta);
        $meta->meta; 
        $ideProyecto=$meta->ide_proyecto;
        $objetivos= PlnObjetivoMeta::with("objetivo")->where("ide_proyecto_meta",$ideProyectoMeta)->get();      
        return view('planificacionobjetivos',array('items'=>$objetivos,'meta'=>$meta->meta->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta));    
    }
    
    public function areaObjetivo($ideObjetivoMeta){
        $objetivo= PlnObjetivoMeta::find($ideObjetivoMeta);
        $objetivo->objetivo; 
        $ideProyecto=$objetivo->ide_proyecto;
        $ideProyectoMeta=$objetivo->ide_proyecto_meta;     
        $areas= PlnAreaObjetivo::with("area")->where("ide_objetivo_meta",$ideObjetivoMeta)->get();

        return view('planificacionarea',array('items'=>$areas,'objetivo'=>$objetivo->objetivo->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta));    
    }
    
    public function indicadorArea($ideAreaObjetivo){
        $area= PlnAreaObjetivo::find($ideAreaObjetivo);
        $area->area;
        $area->objetivoMeta;
        $ideProyecto=$area->ide_proyecto;
        $ideProyectoMeta=$area->objetivoMeta->ide_proyecto_meta;
        $ideObjetivoMeta=$area->ide_objetivo_meta;
        Log::info('atnes de query');
        $indicadores=PlnIndicadorArea::with("indicador")->where("ide_area_objetivo",$ideAreaObjetivo)->get();
        Log::info('fin traer indicador');
        return view('planificacionindicadores',array('items'=>$indicadores,'area'=>$area->area->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta,'ideAreaObjetivo'=>$ideAreaObjetivo));    
    }
   
    public function deleteMeta($ideProyectoMeta){
        $item = PlnProyectoMeta::destroy($ideProyectoMeta);
        return response()->json($item);
    }
    
    public function deleteObjetivo($ideObjetivoMeta){
        $item = PlnObjetivoMeta::destroy($ideObjetivoMeta);
        return response()->json($item);
    }
    
    public function deleteArea($ideAreaObjetivo){
        $item = PlnAreaObjetivo::destroy($ideAreaObjetivo);
        return response()->json($item);
    }
    
    public function deleteIndicador($ideIndicadorArea){
        $item = PlnIndicadorArea::destroy($ideIndicadorArea);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CfgProducto::find($id);
        return response()->json($item);
    }
    
    public function addPlantilla(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();
        $item =  CfgProducto::create($data);
        return response()->json($item);
    }
    
    public function retriveAllMetas(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $metas=$this->metasPorProyecto($ideProyecto);
        return response()->json($metas);
    }
    
    public function retriveAllObjetivos(Request $request){
        $ideProyectoMeta=$request->ide_proyecto_meta;
        $objetivos=$this->objetivoPorMeta($ideProyectoMeta);
        return response()->json($objetivos);
    }
    
    public function retriveAllAreas(Request $request){
        $ideObjetivoMeta=$request->ide_objetivo_meta;
        $areas=$this->areaPorObjetivo($ideObjetivoMeta);
        return response()->json($areas);
    }
    
    public function retriveAllIndicadores(Request $request){
        $ideAreaObjetivo=$request->ide_area_objetivo;
        $indicadores=$this->indicadorPorArea($ideAreaObjetivo);
        return response()->json($indicadores);
    }
    
    public function updateMeta(Request $request,$id){
        $item= PlnProyectoMeta::find($id);
        $item->ind_obligatorio=$request->ind_obligatorio;       
        $item->save();
        return response()->json($item);       
    }
    
    public function addMeta(Request $request){
        $listaMetas=$request->metas;
        $ideProyecto=$request->ide_proyecto;
        Log::info("Guardando metas: ".count($listaMetas));
        $metas=array();
        foreach($listaMetas as $meta){
            Log::info($meta);
            $proyectoMeta=new PlnProyectoMeta;
            $proyectoMeta->ide_proyecto=$ideProyecto;
            $proyectoMeta->ide_meta=$meta['ide_meta'];
            $proyectoMeta->ind_obligatorio=  HPMEConstants::SI;
            $proyectoMeta->save();
            $proyectoMeta->meta;
            $metas[]=$proyectoMeta;
        }
        return response()->json($metas);
    }
    
    public function addObjetivo(Request $request){
        $listaItems=$request->items;
        $ideProyectoMeta=$request->ide_proyecto_meta;
        Log::info("Guardando Objetivos: ".count($listaItems));
        $proyectoMeta=  PlnProyectoMeta::find($ideProyectoMeta);
        $items=array();
        foreach($listaItems as $item){
            Log::info($item);
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
    
    public function addArea(Request $request){
        $listaItems=$request->items;
        $ideObjetivoMeta=$request->ide_objetivo_meta;
        Log::info("Guardando area: ".count($listaItems));
        $objetivoMeta= PlnObjetivoMeta::find($ideObjetivoMeta);
        $items=array();
        foreach($listaItems as $item){
            Log::info($item);
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
    
    public function addIndicador(Request $request){
        $listaItems=$request->items;
        $ideAreaObjetivo=$request->ide_area_objetivo;
        $areaObjetivo= PlnAreaObjetivo::find($ideAreaObjetivo);
        Log::info("buscadon ".$ideAreaObjetivo);
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

        public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}