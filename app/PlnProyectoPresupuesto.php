<?php

namespace App;

class PlnProyectoPresupuesto extends BaseModel
{
    protected $primaryKey = 'ide_proyecto_presupuesto';
    protected $table = 'pln_proyecto_presupuesto';
    protected $fillable = array('fecha_proyecto','fecha_cierre','descripcion','ide_proyecto_planificacion');
    public $timestamps = false;
   
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoPlanificacion','ide_proyecto_planificacion');
    }
    
}