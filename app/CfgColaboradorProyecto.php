<?php

namespace App;

class CfgColaboradorProyecto extends BaseModel
{
    protected $primaryKey = 'ide_colaborador';
    protected $table = 'cfg_colaborador_proyecto';
    protected $fillable = array('tipo','nombres','apellidos','ide_departamento','ide_puesto');
    public $timestamps = false;
    
    public function departamento(){
        return $this->belongsTo('App\CfgDepartamento', 'ide_departamento');     
    }
    
    public function puesto(){
        return $this->belongsTo('App\CfgPuesto','ide_puesto');
    }
}