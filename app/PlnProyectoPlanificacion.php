<?php

namespace App;

class PlnProyectoPlanificacion extends BaseModel
{
    protected $primaryKey = 'ide_proyecto';
    protected $table = 'pln_proyecto_planificacion';
    protected $fillable = array('fecha_proyecto','fecha_cierre','descripcion','ide_usuario_creacion','estado','ide_lista_periodicidad');
    public $timestamps = false;
    
    public function periodicidad(){
        return $this->belongsTo('App\CfgListaValor','ide_lista_periodicidad');
    }
}