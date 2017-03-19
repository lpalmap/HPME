<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\MonArchivoProductoPeriodo;
use App\HPMEConstants;
use App\MonPeriodoRegion;
use App\PresupuestoConstants;

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
        Log::info($files);

        $this->validacion=array();
        $this->validacion['filas']=0;
        foreach ($files as $file){
            Log::info("Leer excel");
            Excel::load($file, function($reader) {
                //$reader->skip(1); 
                // Loop through all sheets
                $reader->each(function($sheet){
                        //$this->filas++;
                        Log::info("Recorriendo fila");
                        $fila= array_values($sheet->toArray());
                        $this->validacion['filas']=$this->validacion['filas']+1;
                        if(count($fila)>=9){
                            $cuenta=$fila[PresupuestoConstants::IMPORT_CODIGO_CUENTA];
                            $periodo=$fila[PresupuestoConstants::IMPORT_PERIODO];
                            $base=$fila[PresupuestoConstants::IMPORT_BASE];
                            $departamento_l1=$fila[PresupuestoConstants::IMPORT_L1];
                            $clase_l2=$fila[PresupuestoConstants::IMPORT_L2];
                            $empleado_l4=$fila[PresupuestoConstants::IMPORT_L4];
                            Log::info("Fila cuenta: $cuenta periodo: $periodo base: $base depto: $departamento_l1 clase: $clase_l2 emplado: $empleado_l4");
                        }
                        
                        //$totales[]=$row;
                    // Loop through all rows
//                    $sheet->each(function($row) use ($totales) {
//                        $this->filas++;
//                        Log::info($row);
//                        $totales[]=$row;
//                    });

                });
            //Log::info("Filas $this->filas");
            
//        // Getting all results
//            $results = $reader->get();
//
//        // ->all() is a wrapper for ->get() and will work the same
//            $results = $reader->all();
//            Log::info($results);
        });
        }
        $result=$this->validacion;
        Log::info($this->validacion);
        $this->validacion=null;
        return response()->json($result);
    }
}