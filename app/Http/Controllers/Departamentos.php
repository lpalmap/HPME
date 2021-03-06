<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfgDepartamento;
use App\SegUsuario;
use App\HPMEConstants;
use Illuminate\Support\Facades\Log;
use App\PrivilegiosConstants;

class Departamentos extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $departamentos=CfgDepartamento::with(['director','contador'])->get(); 
        //$user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        //$usuarios = $user->selectQuery(HPMEConstants::USUARIO_REGION_QUERY, array());
        return view('departamentos',array('items'=>$departamentos));
    }
    
    public function delete($id){
        $item = CfgDepartamento::find($id);
        //$item->administradores()->detach();
        CfgDepartamento::destroy($id);
        return response()->json($item);
    }
    
    public function retriveAllAdmin(Request $request){
        $user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        $usuarios = $user->selectQuery(HPMEConstants::USUARIO_DEPARTAMENTO_QUERY, array());//array('usuarioRol'=>'DIRECTOR DEPARTAMENTO'));
        $contadores=$user->selectQuery(HPMEConstants::USUARIO_CONTADOR_QUERY, array('idePrivilegio'=>  PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO));//array('usuarioRol'=>'DIRECTOR DEPARTAMENTO'));;
        return response()->json(array('admins'=>$usuarios,'contadores'=>$contadores));     
    }
    
    public function retrive($id){
        $item = CfgDepartamento::find($id);
        $item->director;
        $item->contador;
        $user=new SegUsuario();
        //Obtiene la lista de usuarios que pueden ser asignados como administradores (Que no estan asignados a una region)
        $usuarios=$user->selectQuery(HPMEConstants::USUARIO_DEPARTAMENTO_USUARIO_QUERY,array('ideUsuario'=>$item->ide_usuario_director));// array('usuarioRol'=>'DIRECTOR DEPARTAMENTO'));       
        if(is_null($item->ide_usuario_contador)){
            $contadores=$user->selectQuery(HPMEConstants::USUARIO_CONTADOR_QUERY, array('idePrivilegio'=>  PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO));//array('usuarioRol'=>'DIRECTOR DEPARTAMENTO'));;
        }else{
            $contadores=$user->selectQuery(HPMEConstants::USUARIO_CONTADOR_USUARIO_QUERY,array('idePrivilegio'=>  PrivilegiosConstants::PRESUPUESTO_CONSULTA_CONTADOR_DEPARTAMENTO,'ideUsuario'=>$item->ide_usuario_contador));
        }
                
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
        
        $result=array('item'=>$item,'users'=>$usuarios,'contadores'=>$contadores);
        return response()->json($result);
    }
    
    public function add(Request $request){
        $this->validateRequest($request);
        $data = $request->toArray();  
        if($request->ide_usuario_director<=0){
            return response()->json(array('error'=>'Debe seleccionar un usuario como director del departamento.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        $item = CfgDepartamento::create($data);
        if($request->ide_usuario_director>0){
            $item->ide_usuario_director=$request->ide_usuario_director;
        }
        if($request->ide_usuario_contador>0){
            $item->ide_usuario_contador=$request->ide_usuario_contador;
        }else{
            $item->ide_usuario_contador=NULL;
        }       
        $item->director;
        $item->contador;
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $item= CfgDepartamento::find($id);
        if($request->ide_usuario_director>0){
            $this->validateRequestUpdate($request,$request->ide_usuario_director,$id);
            $item->ide_usuario_director=$request->ide_usuario_director;
        }else{
            return response()->json(array('error'=>'Debe seleccionar un usuario como director del departamento.'), HPMEConstants::HTTP_AJAX_ERROR);
        }
        if($request->ide_usuario_contador>0){
            $item->ide_usuario_contador=$request->ide_usuario_contador;
        }else{
            $item->ide_usuario_contador=NULL;
        }
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion; 
        $item->codigo_interno=$request->codigo_interno;
        $item->save();
        $item->director;
        $item->contador;
        return response()->json($item);       
    }
    
    public function validateRequest($request){
        $rules=[
        'nombre' => 'required|max:100',
        'descripcion' => 'required|max:200',
        'codigo_interno' => 'max:150'    
        ];
        $messages=[
        'required' => 'Debe ingresar :attribute.',
        'max'  => 'La capacidad del campo :attribute es :max'
        ];
        $this->validate($request, $rules,$messages);        
    }
    
    public function validateRequestUpdate($request,$ideUsuario,$ideRegion){                
          //Log::info('ide user '.$ideUsuario." reg".$ideRegion);
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
            //'ide_usuario_director' => 'unique:cfg_departamento,ide_usuario_director,'.$ideRegion.',ide_departamento',
            'nombre' => 'required|max:100',
            'descripcion' => 'required|max:100',
            'codigo_interno' => 'max:150'
        ];
        $messages=[
            'required' => 'Debe ingresar :attribute.',
            'max'  => 'La capacidad del campo :attribute es :max'
            //'unique' => 'El usuario ya fue asignado a otro departamento.'
        ];
        $this->validate($request, $rules,$messages);        
    }
}