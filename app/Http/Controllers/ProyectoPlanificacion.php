<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\CfgProducto;
use App\PlnProyectoPlanificacion;
use App\CfgListaValor;
use App\PlnProyectoMeta;
use App\CfgMeta;
use App\HPMEConstants;

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
    
    public function metas(){
        return view('planificacionmetas');
    }
    
    public function metasProyecto($ideProyecto){
        Log::info("Buscando proyecto $ideProyecto");
        $proyecto=  PlnProyectoPlanificacion::findOrFail($ideProyecto);
        //$metas=  PlnProyectoMeta::where('ide_proyecto',$ideProyecto)->get());
        //$metas=  PlnProyectoMeta::all()->where('ide_proyecto', $ideProyecto)->get();
        $metas = PlnProyectoMeta::with("meta")->where('ide_proyecto', $ideProyecto)->get();
        //$metas =  PlnProyectoMeta::all();
//        Log::info('Metasdddd '.  count($metas)); 
//        foreach ($metas as $meta){
//            Log::info("meta,,,,");
//            Log::info("ide proyecto ".$meta->ide_proyecto_meta);
//        }
//        $metas=  PlnProyectoMeta::find(1);
//        Log::info($metas->meta->descripcion);
       $mm=$this->metasPorProyecto($ideProyecto); 
//       Log::info("resulta ".count($mm) );
//       foreach ($mm as $m){
//        Log::info($m->ide_meta);
//        Log::info($m->descripcion);
//       }
//       
//       Log::info("astes de view"); 
        return view('planificacionmetas',array('items'=>$metas,'proyecto'=>$proyecto->descripcion,'itemsSelect'=>$mm,'ideProyecto'=>$ideProyecto));
    }
    
    public function metasPorProyecto($ideProyecto){
        $metas=  new CfgMeta();;
        $params = array("ideProyecto"=>$ideProyecto);
        return $metas->selectQuery(HPMEConstants::META_PROYECTO_QUERY,$params);
    }
    
    public function objetivos(){
        return view('planificacionobjetivos');
    }
    
    public function areas(){
        return view('planificacionarea');
    }
    
    public function indicadores(){
        return view('planificacionindicadores');
    }
    
    public function productos(){
        return view('planificacionproductos');
    }

    public function deleteMeta($ideProyectoMeta){
        $item = PlnProyectoMeta::destroy($ideProyectoMeta);
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