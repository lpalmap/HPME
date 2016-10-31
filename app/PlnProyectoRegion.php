<?php

namespace App;

class PlnProyectoRegion extends BaseModel
{
    protected $primaryKey = 'ide_proyecto_region';
    protected $table = 'pln_proyecto_region';
    protected $fillable = array('ide_region','ide_proyecto_planificacion','ide_usuario_creacion','','fecha_ingreso','fecha_aprobacion','estado');
    public $timestamps = false;
    
    public function region(){
        return $this->belongsTo('App\CfgRegion','ide_region');
    }
    
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoPlanificacion','ide_proyecto_planificacion');
    }
    
    public function usuario(){
        return $this->belongsTo('App\SegUsuario','ide_usuario_creacion');
    }
}
