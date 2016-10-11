<?php

namespace App;

class PlnIndicadorArea extends BaseModel
{
    protected $primaryKey ='ide_indicador_area' ;
    protected $table = 'pln_indicador_area';
    protected $fillable = array('ide_area_objetivo','ide_proyecto','ide_meta','ide_objetivo','ide_area','ide_indicador');
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
    
    public function area(){
        return $this->belongsTo('App\CfgAreaAtencion','ide_area');
    }
    
    public function indicador(){
        return $this->belongsTo('App\CfgIndicador','ide_indicador');
    }
    
    
    
}
