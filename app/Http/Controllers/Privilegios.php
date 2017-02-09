<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SegRol;
use Illuminate\Support\Facades\Log;
use App\HPMEConstants;
use Illuminate\Support\Facades\DB;

class Privilegios extends Controller
{
    //
    //Se cargan los roles y los privilegios asociados a cada uno
    public function index(){
        $roles=SegRol::all();
//        Log::info('Privilegios?');
//        foreach ($roles as $rol){
//            Log::info('Rol: '.$rol->descripcion);
//            Log::info($rol->privilegios);
//        }
        
        return view('privilegios',array('items'=>$roles));
    }
    
    public function retrivePrivilegios($id){
        $rol= SegRol::find($id);
        $rol->privilegios;
        $params=array('ideRol'=>$id);
        $privilegios=DB::select(HPMEConstants::PRIVILEGIOS_SIN_ASIGNAR_ROL,$params);
        //Retorna rol con sus privilegios y la lista de privilegios que no han sido asignados al rol "pendientes"
        return response()->json(array('rol'=>$rol,'pendientes'=>$privilegios));
    }
    
     public function update(Request $request){
        $item= SegRol::find($request->ide_rol);
        $privilegios=$request->privilegios;
        if(count($privilegios)>0){
            $item->privilegios()->sync($privilegios);   
        }else{
            $item->privilegios()->detach();
        }      
        $item->save();
        return response()->json($item);       
    }
}