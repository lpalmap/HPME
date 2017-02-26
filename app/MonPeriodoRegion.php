<?php

namespace App;

class MonPeriodoRegion extends BaseModel
{
    protected $primaryKey ='ide_periodo_region' ;
    protected $table = 'mon_periodo_region';
    protected $fillable = array('fecha_aprobacion','estado','ide_periodo_monitoreo','ide_proyecto_region','descripcion');
    public $timestamps = false;
    
    public function periodo(){
        return $this->belongsTo('App\MonProyectoPeriodo','ide_periodo_monitoreo');
    }
    
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoRegion','ide_proyecto_region');
    }
    
}
