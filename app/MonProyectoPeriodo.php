<?php

namespace App;

class MonProyectoPeriodo extends BaseModel
{
    protected $primaryKey ='ide_periodo_monitoreo' ;
    protected $table = 'mon_proyecto_periodo';
    protected $fillable = array('fecha_habilitacion','fecha_cierre','estado','no_periodo','ide_proyecto','descripcion');
    public $timestamps = false;
    
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoPlanificacion','ide_proyecto');
    }
    
}
