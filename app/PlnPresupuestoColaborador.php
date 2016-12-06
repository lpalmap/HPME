<?php

namespace App;

class PlnPresupuestoColaborador extends BaseModel
{
    protected $primaryKey = 'ide_presupuesto_colaborador';
    protected $table = 'pln_presupuesto_colaborador';
    protected $fillable = array('fecha_ingreso','ide_presupuesto_departamento','ide_colaborador');
    public $timestamps = false;
    
    public function presupuesto(){
        return $this->belongsTo('App\PlnPresupuestoDepartamento','ide_presupuesto_departamento');
    }
    
    public function colaborador(){
        return $this->belongsTo('App\CfgColaboradorProyecto','ide_colaborador');
    }
    
}