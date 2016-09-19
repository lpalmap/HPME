<?php

namespace App;

class PlnProyectoPlanificacion extends BaseModel
{
    protected $primaryKey = 'ide_proyecto';
    protected $table = 'pln_proyecto_planificacion';
    protected $fillable = array('fecha_proyecto','descripcion','ide_usuario_creacion','periodo_planificacion');
    public $timestamps = false;
}