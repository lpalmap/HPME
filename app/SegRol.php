<?php

namespace App;

class SegRol extends BaseModel
{
    protected $primaryKey = 'ide_rol';
    protected $table = 'seg_rol';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
    
    public function privilegios(){
        return $this->belongsToMany('App\SegPrivilegio', 'seg_rol_privilegio', 'ide_rol','ide_privilegio');     
    }  
}