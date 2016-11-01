<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\CfgProducto;
use App\HPMEConstants;
use App\PlnIndicadorArea;
use App\PlnProductoIndicador;
use App\CfgRegion;

class PlanificacionProducto extends Controller
{
   public function productoArea($ideIndicadorArea){
        $indicador= PlnIndicadorArea::find($ideIndicadorArea);
        $indicador->indicador;
        $indicador->areaObjetivo;
        $indicador->areaObjetivo->objetivoMeta;
        $ideProyecto=$indicador->ide_proyecto;
        $ideProyectoMeta=$indicador->areaObjetivo->objetivoMeta->ide_proyecto_meta;
        $ideObjetivoMeta=$indicador->areaObjetivo->ide_objetivo_meta;
        $ideAreaObjetivo=$indicador->ide_area_objetivo;
        $productos=  PlnProductoIndicador::with("producto")->where("ide_indicador_area",$ideIndicadorArea)->get();
        $rol=  request()->session()->get('rol');
        return view('planificacionproductos',array('items'=>$productos,'indicador'=>$indicador->indicador->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta,'ideAreaObjetivo'=>$ideAreaObjetivo,'ideIndicadorArea'=>$ideIndicadorArea,'rol'=>$rol));    
    }
    
    public function addProducto(Request $request){
        $listaItems=$request->items;
        $ideIndicadorArea=$request->ide_indicador_area;
        $indicadorArea= PlnIndicadorArea::find($ideIndicadorArea);
        $this->validateRequest($request, $indicadorArea->ide_proyecto);
        $items=array();
        foreach($listaItems as $item){
            $nItem=new PlnProductoIndicador();
            $nItem->ide_indicador_area=$ideIndicadorArea;
            $nItem->ide_proyecto=$indicadorArea->ide_proyecto;
            $nItem->ide_meta=$indicadorArea->ide_meta;
            $nItem->ide_objetivo=$indicadorArea->ide_objetivo;
            $nItem->ide_area=$indicadorArea->ide_area;
            $nItem->ide_indicador=$indicadorArea->ide_indicador;
            $nItem->ide_producto=$item['ide_producto'];
            $nItem->save();
            $nItem->producto;
            $items[]=$nItem;
        }
        return response()->json($items);
    }
    
    public function producotPorIndicador($ideProyecto){
        $producto=  new CfgProducto();
        $params = array("ideProyecto"=>$ideProyecto);
        return $producto->selectQuery(HPMEConstants::PRODUCTO_INDICADOR_QUERY,$params);
    }
    
    public function retriveAllProductos(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $productos=$this->producotPorIndicador($ideProyecto);
        return response()->json($productos);
    }
    
    public function deleteProducto($ideProductoIndicador){
        $item = PlnProductoIndicador::destroy($ideProductoIndicador);
        return response()->json($item);
    }
    
    public function validateRequest($request,$ide_proyecto){
        $rules=[
            'items.*.ide_producto' => 'unique:pln_producto_indicador,ide_producto,NULL,ide_producto,ide_proyecto,'.$ide_proyecto,
        ];
        $messages=[
            'unique' => 'El producto ya fue agregado por otro usuario/sessi&oacute;n.'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    public function addDetalle(Request $request){
        $ideProyecto=$request->ideProyecto;
        $user=Auth::user();
        $ideUsuario=$user->ide_usuario;
        Log::info("Proyecto $ideProyecto usuario $ideUsuario");
        $regionQuery=new CfgRegion();
        $params=array('ideUsuario'=>$ideUsuario);
        $regiones=$regionQuery->selectQuery(HPMEConstants::REGION_USUARIO_ADMINISTRADOR_QUERY, $params);
        Log::info($regiones);
        if(count($regiones)>0){
            $ideRegionAdmin=$regiones[0]->ide_region;
            response()->json(array('region'=>$ideRegionAdmin));
        }else{
            response()->json(array('error'=>'El usuario no es adminitrador de una regi&oacute;n'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        
    }
    
}
