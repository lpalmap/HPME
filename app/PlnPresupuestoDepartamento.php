<?php

namespace App;

class PlnPresupuestoDepartamento extends BaseModel
{
    protected $primaryKey = 'ide_presupuesto_departamento';
    protected $table = 'pln_presupuesto_departamento';
    protected $fillable = array('fecha_ingreso','fecha_aprobacion','descripcion','estado','ide_departamento','ide_proyecto_presupuesto');
    public $timestamps = false;
    
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoPresupuesto','ide_proyecto_presupuesto');
    }
    
    public function departamento(){
        return $this->belongsTo('App\CfgDepartamento','ide_departamento');
    }
    
}