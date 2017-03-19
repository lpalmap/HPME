<?php

namespace App;

class MonArchivoPresupuesto extends BaseModel
{
    protected $primaryKey ='ide_archivo_presupuesto' ;
    protected $table = 'mon_archivo_presupuesto';
    protected $fillable = array('nombre','fecha','ide_periodo_monitoreo','ide_usuario');
    public $timestamps = false;   
}