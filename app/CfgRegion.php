<?php

namespace App;

class CfgRegion extends BaseModel
{
    protected $primaryKey = 'ide_region';
    protected $table = 'cfg_region';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
    
    public function administradores(){
        return $this->belongsToMany('App\SegUsuario', 'seg_usuario_region', 'ide_region','ide_usuario');     
    }  
    
    public function proyectos(){
        return $this->belongsToMany('App\CfgProyecto', 'cfg_proyecto_region','ide_region', 'ide_proyecto');     
    }
}