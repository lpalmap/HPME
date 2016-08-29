<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\CatMeta;

class Metas extends Controller
{
    //
    //Obtiene metas y crea vista
    public function index(){
        $metas=new CatMeta();
        $data=$metas->all(); 
        return view('metas',array('items'=>$data));
    }
    
    public function delete($id){
        $item = CatMeta::destroy($id);
        return response()->json($item);
    }
    
    public function retrive($id){
        $item = CatMeta::find($id);
        return response()->json($item);
    }
    
    public function add(Request $request){
        $data = $request->toArray();
        $item =  CatMeta::create($data);
        return response()->json($item);
    }
    
    public function update(Request $request,$id){
        $item= CatMeta::find($id);
        $item->nombre=$request->nombre;
        $item->descripcion=$request->descripcion;        
        $item->save();
        return response()->json($item);       
    }
    
}
