<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CfgCuenta;
use Illuminate\Support\Facades\DB;
use App\HPMEConstants;
use Illuminate\Support\Facades\Log;

class Cuentas extends Controller
{
    //Obtiene usuarios y crea vista
    public function index($id=null){
        $cuentas=array();
        $ideCuentaPadre=0;
        $parents=array();
        if(is_null($id)){
            $cuentas= CfgCuenta::where('ide_cuenta_padre',null)->get();
        }else{
            $cuentas= CfgCuenta::where('ide_cuenta_padre',$id)->get();
            $ideCuentaPadre=$id;
            $parents=DB::select(HPMEConstants::CFG_CUENTAS_PARENT,array('ideCuenta'=>$id));            
        }
        Log::info($parents); 
        return view('cuentas',array('cuentas'=>$cuentas,'ideCuentaPadre'=>$ideCuentaPadre,'parents'=>$parents));
    }
    
    public function delete($id){
        $cuenta = CfgCuenta::find($id);
        CfgCuenta::destroy($id);
        return response()->json($cuenta);
    }
    
    public function retrive($id){
        $cuenta = CfgCuenta::find($id);
        return response()->json($cuenta);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        
        $data = $request->toArray(); 
        Log::info($data);
        if($data['ide_cuenta_padre']==0){
            $data['ide_cuenta_padre']=null;
        }
        Log::info('cuenta padre.. '.$data['ide_cuenta_padre']);
        Log::info($data);
        $cuenta= CfgCuenta::create($data);
        return response()->json($cuenta);
    }
    
    public function update(Request $request,$id){
        $this->validateRequest($request);
        $cuenta= CfgCuenta::find($id);
        $cuenta->cuenta=$request->cuenta;
        $cuenta->nombre=$request->nombre;
        $cuenta->descripcion=$request->descripcion;
        $cuenta->ind_consolidar=$request->ind_consolidar;
        $cuenta->estado=$request->estado;
        $cuenta->codigo_interno=$request->codigo_interno;
        $cuenta->save();
        return response()->json($cuenta);       
    }
    
    public function validateRequest($request){                
        $rules=[
            'nombre' => 'required|max:250',
            'codigo_interno' => 'required|max:150',
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute. de la cuenta',
            'max'  => 'La capacidad del campo :attribute es :max'
        ];
        $this->validate($request, $rules,$messages);        
    }
}