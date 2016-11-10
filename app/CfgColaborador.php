<?php

namespace App;

class CfgColaborador extends BaseModel
{
    protected $primaryKey = 'ide_colaborador';
    protected $table = 'cfg_colaborador';
    protected $fillable = array('nombres','apellidos','ide_departamento');
    public $timestamps = false;
    
    public function departamento(){
        return $this->belongsTo('App\CfgDepartamento', 'ide_departamento');     
    }
}