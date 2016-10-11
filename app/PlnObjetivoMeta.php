<?php

namespace App;

class PlnObjetivoMeta extends BaseModel
{
    protected $primaryKey = 'ide_objetivo_meta';
    protected $table = 'pln_objetivo_meta';
    protected $fillable = array('ide_proyecto_meta','ide_proyecto','ide_meta','ide_objetivo');
    public $timestamps = false;
    
    public function meta(){
        return $this->belongsTo('App\CfgMeta','ide_meta');
    }
    
    public function proyecto(){
        return $this->belongsTo('App\PlnProyectoPlanificacion','ide_proyecto');
    }
    
    public function objetivo(){
        return $this->belongsTo('App\CfgObjetivo','ide_objetivo');
    }
}