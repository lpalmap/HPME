<?php

namespace App;

class CfgProyecto extends BaseModel
{
    protected $primaryKey = 'ide_proyecto';
    protected $table = 'cfg_proyecto';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
    
    
    public function regiones(){
        return $this->belongsToMany('App\CfgRegion', 'cfg_proyecto_region', 'ide_proyecto', 'ide_region');     
    }
}