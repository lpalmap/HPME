<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlnProyectoPlanificacion;
use App\PlnProyectoMeta;
use App\CfgMeta;
use App\HPMEConstants;

class PlanificacionMeta extends Controller
{
    //Obtiene metas y crea vista
    
    public function metasProyecto($ideProyecto){
        $proyecto=  PlnProyectoPlanificacion::findOrFail($ideProyecto);
        $metas = PlnProyectoMeta::with("meta")->where('ide_proyecto', $ideProyecto)->get();
        $rol=  request()->session()->get('rol');
        return view('planificacionmetas',array('items'=>$metas,'proyecto'=>$proyecto->descripcion,'ideProyecto'=>$ideProyecto,'rol'=>$rol));
    }
    
    public function metasPorProyecto($ideProyecto){
        $metas=  new CfgMeta();
        $params = array("ideProyecto"=>$ideProyecto);
        return $metas->selectQuery(HPMEConstants::META_PROYECTO_QUERY,$params);
    }
    
    public function deleteMeta($ideProyectoMeta){
        $item = PlnProyectoMeta::destroy($ideProyectoMeta);
        return response()->json($item);
    }
    
    public function retriveAllMetas(Request $request){
        $ideProyecto=$request->ide_proyecto;
        $metas=$this->metasPorProyecto($ideProyecto);
        return response()->json($metas);
    }
    
    public function updateMeta(Request $request,$id){
        $item= PlnProyectoMeta::find($id);
        $item->ind_obligatorio=$request->ind_obligatorio;       
        $item->save();
        return response()->json($item);       
    }
    
    public function addMeta(Request $request){
        $this->validateRequest($request,$request->ide_proyecto);
        $listaMetas=$request->metas;
        $ideProyecto=$request->ide_proyecto;
        $metas=array();
        foreach($listaMetas as $meta){
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
    
    public function validateRequest($request,$ide_proyecto){
        $rules=[
            'metas.*.ide_meta' => 'unique:pln_proyecto_meta,ide_meta,NULL,ide_meta,ide_proyecto,'.$ide_proyecto,
        ];
        $messages=[
            'unique' => 'La meta ya fue agregada por otro usuario/sessi&oacute;n.'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
}