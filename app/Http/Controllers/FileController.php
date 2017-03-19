<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\MonArchivoProductoPeriodo;
use App\HPMEConstants;
use App\MonPeriodoRegion;
use App\PresupuestoConstants;
use App\PlnProyectoPresupuesto;
use App\MonArchivoPresupuesto;

class FileController extends Controller
{
    private $validacion;
    //
    //Obtiene metas y crea vista
    public function upload(Request $request){
        //request()->file('files')->move('archivos','test1.png');
        $files = $request->allFiles();
        
        $detalle=$request->ide_region_producto_detalle;
        $estadoPeriodoRegion=  MonPeriodoRegion::where('ide_periodo_region','=',$request->ide_periodo_region)->pluck('estado')->first();
        if($estadoPeriodoRegion!==HPMEConstants::ABIERTO){
            return response()->json(array('error'=>'El periodo de la regi&oacute;n debe estar '.HPMEConstants::ABIERTO.' para subir archivos.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        date_default_timezone_set(HPMEConstants::TIME_ZONE);
        $archivos=array();
        foreach ($files as $file){
            $filename=$file->getClientOriginalName();
            $new_file=  new MonArchivoProductoPeriodo;
            $new_file->nombre=$filename;
            $new_file->fecha=date(HPMEConstants::DATETIME_FORMAT,  time());
            //$new_file->extension=pathinfo($filename, PATHINFO_EXTENSION);
            $new_file->ide_region_producto_detalle=$detalle;
            $new_file->save();
            $destroy=false;
            try{
                $file->move('archivos', 'm'.$new_file->ide_archivo_producto);
            } catch(\Exception $e){
                $destroy=true;
            }  
            if($destroy){
                MonArchivoProductoPeriodo::destroy($new_file->ide_archivo_producto);
            }else{
                $archivos[]=$new_file;
            }
        }
        if(count($archivos)==0){
            return response()->json(array('error'=>'Debe seleccionar un archivo.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        return response()->json(array('archivos'=>$archivos));
    } 
    
    public function monitoreoDownload($id){
        $file=MonArchivoProductoPeriodo::find($id);
        return response()->download('archivos/m'.$id,$file->nombre);
    }
    
    public function deleteArchivoMonitoreo(Request $request){
        $estadoPeriodo=  MonPeriodoRegion::where('ide_periodo_region','=',$request->ide_periodo_region)->pluck('estado')->first();
        if($estadoPeriodo!==HPMEConstants::ABIERTO){
            return response()->json(array('error'=>'Solo se puede borrar archivos cuando el periodo para la regi&oacute;n est&aacute; '+HPMEConstants::ABIERTO+'.'), HPMEConstants::HTTP_AJAX_ERROR);
        }    
        File::delete('archivos/m'.$request->ide_archivo_producto);
        MonArchivoProductoPeriodo::destroy($request->ide_archivo_producto);
        return response()->json();
    }
    
    public function verificarEjecucion(Request $request){
        $files=$request->allFiles();
        if(count($files)==0){
            return response()->json(array('error'=>'Debe seleccionar un archivo para verificarlo'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $ideProyectoPresupuesto=  PlnProyectoPresupuesto::where('ide_proyecto_planificacion','=',$request->ide_proyecto_planificacion)->pluck('ide_proyecto_presupuesto')->first();
        $this->validacion=array();
        $this->validacion['filas']=0;
        $this->validacion['total_encontrado']=0;
        $this->validacion['total_noencontrado']=0;
        $this->validacion['monto_encontrado']=0.0;
        $this->validacion['monto_noencontrado']=0.0;
        foreach ($files as $file){
            Excel::load($file, function($reader) use ($ideProyectoPresupuesto){
                $reader->each(function($sheet) use ($ideProyectoPresupuesto){
                        $fila= array_values($sheet->toArray());
                        $this->validacion['filas']=$this->validacion['filas']+1;
                        if(count($fila)>=9){
                            $cuenta=$fila[PresupuestoConstants::IMPORT_CODIGO_CUENTA];
                            $periodo= (int)substr($fila[PresupuestoConstants::IMPORT_PERIODO],5);
                            $base=$fila[PresupuestoConstants::IMPORT_BASE];
                            $departamento_l1=$fila[PresupuestoConstants::IMPORT_L1];
                            $clase_l2=$fila[PresupuestoConstants::IMPORT_L2];
                            $empleado_l4=$fila[PresupuestoConstants::IMPORT_L4];
                            $count=DB::select(PresupuestoConstants::PRESUPUESTO_COUNT_DETALLE_PERIODO,array('idePresupuestoDepartamento'=>$ideProyectoPresupuesto,'cuenta'=>$cuenta,'clase'=>$clase_l2,'departamento'=>$departamento_l1,'empleado'=>$empleado_l4,'periodo'=>$periodo));
//                            if($cuenta=='412102'){
//                                Log::info("Fila cuenta: $cuenta periodo: $periodo base: $base depto: $departamento_l1 clase: $clase_l2 emplado: $empleado_l4");
//                            }
                            if($count[0]->detalles>0){
                                $this->validacion['total_encontrado']=$this->validacion['total_encontrado']+1;
                                 $this->validacion['monto_encontrado']=$this->validacion['monto_encontrado']+$base;      
                            }else{
                                //Log::info("No Mayor a 0");
                                $this->validacion['total_noencontrado']=$this->validacion['total_noencontrado']+1;
                                $this->validacion['monto_noencontrado']=$this->validacion['monto_noencontrado']+$base;
                            }
                        }                     
                });
        });
        }
        $result=$this->validacion;
        Log::info($this->validacion);
        $this->validacion=null;
        return response()->json($result);
    }
    
    
    public function aplicarEjecucion(Request $request){
        $files=$request->allFiles();
        if(count($files)==0){
            return response()->json(array('error'=>'Debe seleccionar un archivo para aplicar la ejecuci&oacute;n'), HPMEConstants::HTTP_AJAX_ERROR);
        }

        $ideProyectoPresupuesto=  PlnProyectoPresupuesto::where('ide_proyecto_planificacion','=',$request->ide_proyecto_planificacion)->pluck('ide_proyecto_presupuesto')->first();
        $idePeriodoMonitoreo=$request->ide_periodo_monitoreo;
        $user=Auth::user();
        $ideUsuario=$user->ide_usuario;
        date_default_timezone_set(HPMEConstants::TIME_ZONE);
        foreach ($files as $file){
            Excel::load($file, function($reader) use ($ideProyectoPresupuesto){
                $reader->each(function($sheet) use ($ideProyectoPresupuesto){
                        $fila= array_values($sheet->toArray());
                        $this->validacion['filas']=$this->validacion['filas']+1;
                        if(count($fila)>=9){
                            $cuenta=$fila[PresupuestoConstants::IMPORT_CODIGO_CUENTA];
                            $periodo= (int)substr($fila[PresupuestoConstants::IMPORT_PERIODO],5);
                            $base=$fila[PresupuestoConstants::IMPORT_BASE];
                            $departamento_l1=$fila[PresupuestoConstants::IMPORT_L1];
                            $clase_l2=$fila[PresupuestoConstants::IMPORT_L2];
                            $empleado_l4=$fila[PresupuestoConstants::IMPORT_L4];
                            //Log::info("Fila cuenta: $cuenta periodo: $periodo base: $base depto: $departamento_l1 clase: $clase_l2 emplado: $empleado_l4");
                            DB::select(PresupuestoConstants::PRESUPUESTO_UPDATE_DETALLE_PERIODO,array('idePresupuestoDepartamento'=>$ideProyectoPresupuesto,'base'=>$base,'cuenta'=>$cuenta,'clase'=>$clase_l2,'departamento'=>$departamento_l1,'empleado'=>$empleado_l4,'periodo'=>$periodo));
                        }                       
                });

        });
            $new_file=  new MonArchivoPresupuesto;
            $new_file->nombre=$file->getClientOriginalName();
            $new_file->fecha=date(HPMEConstants::DATETIME_FORMAT,  time());
            //$new_file->extension=pathinfo($filename, PATHINFO_EXTENSION);
            $new_file->ide_periodo_monitoreo=$idePeriodoMonitoreo;
            $new_file->ide_usuario=$ideUsuario;
            $new_file->save();
        }
        
        return response()->json();
    }
    
}