<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CfgProducto;
use App\HPMEConstants;
use App\PlnIndicadorArea;
use App\PlnProductoIndicador;
use App\CfgRegion;
use App\PlnProyectoRegion;
use App\PlnRegionProducto;
use App\PlnRegionProductoDetalle;
use Illuminate\Support\Facades\DB;
use App\PlnProyectoPlanificacion;
use Illuminate\Support\Facades\Log;
use App\PrivilegiosConstants;

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
        $creaPlanificacion=$this->creaPlanificacion();
        $produtosIngresados=array();
        $ingresaPlan=$this->ingresaPlanificacion();
        if($ingresaPlan){
            $region=$this->regionUsuario($indicador->ide_proyecto);
            if(!is_null($region)){
                $ingresados=DB::select(HPMEConstants::PLN_PRODUCTOS_COMPLETADOS,array('ideIndicadorArea'=>$ideIndicadorArea,'ideRegion'=>$region));
                foreach($ingresados as $ingresado){
                    $produtosIngresados[]=$ingresado->ide_producto_indicador;
                }
            }
        }
        return view('planificacionproductos',array('items'=>$productos,'indicador'=>$indicador->indicador->nombre,'ideProyecto'=>$ideProyecto,'ideProyectoMeta'=>$ideProyectoMeta,'ideObjetivoMeta'=>$ideObjetivoMeta,'ideAreaObjetivo'=>$ideAreaObjetivo,'ideIndicadorArea'=>$ideIndicadorArea,'creaPlanificacion'=>$creaPlanificacion,'ingresados'=>$produtosIngresados,'ingresaPlan'=>$ingresaPlan));    
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
    
    private function ingresaPlanificacion(){
        $privilegios=request()->session()->get('privilegios');
        if(isset($privilegios)){
            if(in_array(PrivilegiosConstants::PLANIFIACION_INGRESAR_PLANIFICACION, $privilegios)){
                return TRUE;
            }
        }      
        return FALSE;
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
    
    public function retriveDetalle(Request $request){
        $ideRegion=$this->regionUsuario($request->ide_proyecto);
        if(!is_null($ideRegion)){
            $region=  CfgRegion::find($ideRegion);
            $proyectos=$region->proyectos;
            $ideProyectoRegion=  PlnProyectoRegion::where(array("ide_region"=>$ideRegion,"ide_proyecto_planificacion"=>$request->ide_proyecto))->pluck('ide_proyecto_region')->first();  ;//$regionQuery->selectQuery(HPMEConstants::SI, $params);
            if(!is_null($ideProyectoRegion)){
                $ideRegionProducto=  PlnRegionProducto::where(array('ide_proyecto_region'=>$ideProyectoRegion,'ide_producto_indicador'=>$request->ide_producto_indicador))->pluck('ide_region_producto')->first();  
                if(!is_null($ideRegionProducto)){
                    $regionProducto=  PlnRegionProducto::find($ideRegionProducto);
                    $regionProducto->detalle;
                    return response()->json(array('item'=>$regionProducto,'proyectos'=>$proyectos));
                }
            }
            return response()->json(array('proyectos'=>$proyectos));
            //El usuario es administrador de una region pero no ha ingresado planificacion para un producto.
        }
        //El usuario no es administrador de una region
        
        return response()->json();              
    }
    
    //Retorna el id de la region de la que el usuario es administador
    private function proyectoRegionUsuario($ideProyecto){
        $user=Auth::user();
        $regionQuery=new CfgRegion();
        $regiones=$regionQuery->selectQuery(HPMEConstants::REGION_USUARIO_ADMINISTRADOR_QUERY, array('ideUsuario'=>$user->ide_usuario));
        if(count($regiones)>0){
            $ideRegionAdmin=$regiones[0]->ide_region;
            $ideProyectoRegion=  PlnProyectoRegion::where(array("ide_region"=>$ideRegionAdmin,"ide_proyecto_planificacion"=>$ideProyecto))->pluck('ide_proyecto_region')->first();  ;//$regionQuery->selectQuery(HPMEConstants::SI, $params);
            return $ideProyectoRegion;
        }else{
            return null;
        }
    }
    
    //Retorna el id de la region que el usuario es administrador...
    private function regionUsuario($ideProyecto){
        $user=Auth::user();
        $regionQuery=new CfgRegion();
        $regiones=$regionQuery->selectQuery(HPMEConstants::REGION_USUARIO_ADMINISTRADOR_QUERY, array('ideUsuario'=>$user->ide_usuario));
        if(count($regiones)>0){
            return $regiones[0]->ide_region;           
        }else{
            return null;
        }
    }
    
    public function addDetalle(Request $request){
        $stsProyecto= PlnProyectoPlanificacion::where(array('ide_proyecto'=>$request->ide_proyecto))->pluck('estado')->first();
        if($stsProyecto!=HPMEConstants::PUBLICADO){
            return response()->json(array('error'=>'El proyecto debe estar en estado '.HPMEConstants::PUBLICADO.' para ingresar datos.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $ideProyecto=$request->ide_proyecto;
        $items=$request->items;
        $proyecto=$request->proyecto;
        $user=Auth::user();
        $ideUsuario=$user->ide_usuario;
        $regionQuery=new CfgRegion();
        $params=array('ideUsuario'=>$ideUsuario);
        $regiones=$regionQuery->selectQuery(HPMEConstants::REGION_USUARIO_ADMINISTRADOR_QUERY, $params);
        if(count($regiones)>0){
            $ideRegionAdmin=$regiones[0]->ide_region;
            //['ide_region',$ideRegionAdmin],['ide_proyecto_planificacion',$ideProyecto]
            if($proyecto>0){
                //Se establecio un proyecto para producto no es necesario validar si se ingreso planificacion
            }else{
                $totalItems=  $this->totalItemsProducto($items);
                if($totalItems>0){
                    return response()->json(array('error'=>'Debe seleccionar un proyecto para la planificaci&oacute;n ya que no est&aacute planificando 0 para el producto.'), HPMEConstants::HTTP_AJAX_ERROR);
                }
            }
            
            $ideProyectoRegion=-1;
            $regionProyecto=PlnProyectoRegion::where(array("ide_region"=>$ideRegionAdmin,"ide_proyecto_planificacion"=>$ideProyecto))->select(['ide_proyecto_region','estado'])->get(); //$regionQuery->selectQuery(HPMEConstants::SI, $params);
            $nuevo=false;
            if(count($regionProyecto)==0){
                //return response()->json(array('error'=>'No se ha crado un proyecto para la region'),  HPMEConstants::HTTP_AJAX_ERROR);
                //Si no se ha creado un proyecto para la region se crea uno nuevo
                $proyectoRegion=new PlnProyectoRegion();
                $proyectoRegion->ide_region=$ideRegionAdmin;
                $proyectoRegion->ide_proyecto_planificacion=$ideProyecto;
                $proyectoRegion->ide_usuario_creacion=$ideUsuario;
                $proyectoRegion->fecha_ingreso=date(HPMEConstants::DATE_FORMAT,  time());;
                $proyectoRegion->estado=HPMEConstants::ABIERTO;
                $proyectoRegion->save();
                $ideProyectoRegion=$proyectoRegion->ide_proyecto_region;      
                $nuevo=true;     
            }else{
                $ideProyectoRegion=$regionProyecto[0]['ide_proyecto_region'];
                if($regionProyecto[0]['estado']!=HPMEConstants::ABIERTO){
                    return response()->json(array('error'=>'El proyecto para la regi&oacute;n se encuentra '.$regionProyecto[0]['estado'].' debe estar '.HPMEConstants::ABIERTO.' para ingresar datos.'), HPMEConstants::HTTP_AJAX_ERROR);
                }
            }
            
            $ideRegionProducto=0;
            $ideProductoIndicador=$request->ide_producto_indicador;
            if($nuevo){
                $ideRegionProducto=$this->createRegionProducto($ideProductoIndicador, $ideProyectoRegion, $request->descripcion,$proyecto);
            }else{
                //['ide_proyecto_region'=>$ideProyectoRegion],['ide_producto_indicador'=>$ideProductoIndicador]
                $ideRegionProducto=  PlnRegionProducto::where(array('ide_proyecto_region'=>$ideProyectoRegion,'ide_producto_indicador'=>$ideProductoIndicador))->pluck('ide_region_producto')->first();  
                if(is_null($ideRegionProducto)){
                    $ideRegionProducto=$this->createRegionProducto($ideProductoIndicador, $ideProyectoRegion, $request->descripcion,$proyecto);
                }
                
            }
            
            $regionProducto=  PlnRegionProducto::find($ideRegionProducto);
            $detalles=$regionProducto->detalle;
            if(!$nuevo){
                $regionProducto->descripcion=$request->descripcion;
                if($proyecto>0){
                    $regionProducto->ide_proyecto=$proyecto;
                }else{
                    $regionProducto->ide_proyecto=null;
                }
                $regionProducto->save();
            }
            if(count($detalles)>0){
                $this->updateDetalleProducto($detalles, $items);
            }else{
                $this->createDetalleProducto($ideRegionProducto, $items);
            }       
            return response()->json(array('region_producto'=>$ideRegionProducto));
        }else{
            return response()->json(array('error'=>'El usuario no es administrador de una regi&oacute;n'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        
    }
    
    private function createRegionProducto($ideProductoIndicador,$ideProyectoRegion,$descripcion,$proyecto){
        $regionProducto=new PlnRegionProducto();
        $regionProducto->ide_producto_indicador=$ideProductoIndicador;
        $regionProducto->ide_proyecto_region=$ideProyectoRegion;
        $regionProducto->descripcion=$descripcion;
        if($proyecto>0){
            $regionProducto->ide_proyecto=$proyecto;
        }
        $regionProducto->save();
        return $regionProducto->ide_region_producto;
    }
    
    private function createDetalleProducto($ideRegionProducto,$items){
        $itemsCount=count($items);
        for ($i = 1; $i <=$itemsCount; $i++) {
            $item=new PlnRegionProductoDetalle();
            $item->ide_region_producto=$ideRegionProducto;
            $item->num_detalle=$i;
            $item->valor=$items['item'.$i];
            $item->save();
        } 
    }
    
    private function updateDetalleProducto($detalles,$items){
        foreach($detalles as $detalle){
            $itemKey='item'.$detalle->num_detalle;
            if(isset($items[$itemKey])){
                $itemUpdate=PlnRegionProductoDetalle::find($detalle->ide_region_producto_detalle);
                $itemUpdate->valor=$items[$itemKey];
                $itemUpdate->save();
            }
        }
    }
    
    private function totalItemsProducto($items){
        $itemsCount=count($items);
        $total=0;
        for ($i = 1; $i <=$itemsCount; $i++) {
            $itemKey='item'.$i;
            if(isset($items[$itemKey])){
                $total+=$items[$itemKey];
            }
        } 
        return $total;
    }
    
}
