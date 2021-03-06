<?php

namespace App;

class CfgDepartamento extends BaseModel
{
    protected $primaryKey = 'ide_departamento';
    protected $table = 'cfg_departamento';
    protected $fillable = array('nombre','descripcion','ide_usuario_director','codigo_interno','ide_usuario_contador');
    public $timestamps = false;   
    
    public function director(){
        return $this->belongsTo('App\SegUsuario', 'ide_usuario_director');     
    }
    
    public function contador(){
        return $this->belongsTo('App\SegUsuario', 'ide_usuario_contador');     
    }
}