<?php

namespace App;

class PlnAreaObjetivo extends BaseModel
{
    protected $primaryKey ='ide_area_objetivo' ;
    protected $table = 'pln_area_objetivo';
    protected $fillable = array('ide_objetivo_meta','ide_proyecto','ide_meta','ide_objetivo','ide_area');
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
    
}
