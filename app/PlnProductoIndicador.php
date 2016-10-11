<?php

namespace App;

class PlnProductoIndicador extends BaseModel
{
    protected $primaryKey ='ide_producto_indicador' ;
    protected $table = 'pln_producto_indicador';
    protected $fillable = array('ide_indicador_area','ide_proyecto','ide_meta','ide_objetivo','ide_area','ide_indicador','ide_producto');
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
    
    public function producto(){
        return $this->belongsTo('App\CfgProducto','ide_producto');
    }
 
}
