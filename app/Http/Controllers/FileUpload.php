<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgObjetivo;
use Illuminate\Support\Facades\Log;

class FileUpload extends Controller
{
    //
    //Obtiene metas y crea vista
    public function upload(Request $request){
        //request()->file('files')->move('archivos','test1.png');
        //$files = request()->file('files');
        //Log::info("archivos ".count($files));
        foreach (request()->file('files') as $file){
            Log::info("test");
            $filename=$file->getClientOriginalName();
            Log::info("Subiendo archivos");
            $file->move('archivos', $filename);
        }
        return response()->json("Listo!!!!");
    } 
}