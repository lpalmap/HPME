<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\SegUsuario;

class Usuarios extends Controller
{
    //Obtiene usuarios y crea vista
    public function index(){
        $usuarios=new SegUsuario();
        $data=$usuarios->all(); 
        return view('usuarios',array('usuarios'=>$data));
    }
    
}
