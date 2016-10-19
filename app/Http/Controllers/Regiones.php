<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgRegion;
use App\SegUsuario;
use App\HPMEConstants;
use Illuminate\Support\Facades\Log;

class Regiones extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $regiones=CfgRegion::with('administradores')->get(); 
        //$user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        //$usuarios = $user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY, array());
        return view('regiones',array('items'=>$regiones));
    }
    
    public function delete($id){
        $item =  CfgRegion::find($id);
        $item->administradores()->detach();
        CfgRegion::destroy($id);
        return response()->json($item);
    }
    
    public function retriveAllAdmin(Request $request){
        $user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        $usuarios = $user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY, array());
        return response()->json($usuarios);     
    }
    
    public function retrive($id){
        $item = CfgRegion::find($id);
        $item->administradores;
        $user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        $usuarios=$user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY, array());
//        if(count($item->administradores)>0){
//            $ideUserAdmin=0;
//            foreach ($item->administradores as $admin){
//                $ideUserAdmin=$admin->ide_usuario;
//            }
//            Log::info('BUSCNDO: '.$ideUserAdmin);
//            $usuarios = $user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY_EDIT, array('ideUsuario'=>$ideUserAdmin));
//        }else{
//            $usuarios = $user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY, array('ideUsuario'=>$id));
//        }
        
        $result=array('item'=>$item,'users'=>$usuarios);
        return response()->json($result);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();      
        $item =  CfgRegion::create($data);
        if($request->ide_usuario>0){
            $item->administradores()->attach($request->ide_usuario,['ind_administrador'=>  HPMEConstants::SI]);
        }
        $item->administradores;
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $item= CfgRegion::find($id);
        if($request->ide_usuario>0){
            $this->validateRequestUpdate($request,$request->ide_usuario,$id);
            $item->administradores()->sync([$request->ide_usuario]);
        }else{
            $this->validateRequest($request,$id);
            $item->administradores()->detach();
        }
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion;        
        $item->save();
        $item->administradores;
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        'ide_usuario'=>'unique:seg_usuario_region'
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max',
        'unique' => 'El usuario ya ha sido asignado a otra region'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    public function validateRequestUpdate($request,$ideUsuario,$ideRegion){                
          Log::info('ide user '.$ideUsuario." reg".$ideRegion);
//        'email' => [
//                'required',
//                Rule::unique('users')->ignore($user->id),
//            ],
//        $rules=[
//            'usuario' => [
//                'required',
//                'max:50',
//                Rule::unique('seg_usuario')->ignore($id),
//            ],
//            'nombres' => 'required|max:100',
//            'apellidos' => 'required|max:100'  
//        ];
        //'ide_usuario' => 'unique:seg_usuario_region,'.$ideUsuario.',ide_usuario,ide_regio,'.$ideRegion,
        //'unique' => 'El :attribute ya ha sido utilizado'
        $rules=[
            'ide_usuario' => 'unique:seg_usuario_region,ide_usuario,'.$ideRegion.',ide_region',
            'nombre' => 'required|max:100',
            'descripcion' => 'required|max:100'  
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max',
            'unique' => 'El usuario ya fue asignado a otra region.'
        ];
        $this->validate($request, $rules,$messages);        
    }
}