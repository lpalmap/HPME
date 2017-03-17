<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\MonArchivoProductoPeriodo;
use App\HPMEConstants;
use App\MonPeriodoRegion;

class FileController extends Controller
{
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
}