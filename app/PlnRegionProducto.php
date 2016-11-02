<?php

namespace App;

class PlnRegionProducto extends BaseModel
{
    protected $primaryKey = 'ide_region_producto';
    protected $table = 'pln_region_producto';
    protected $fillable = array('ide_producto_indicador','ide_proyecto_region','ide_proyecto','descripcion');
    public $timestamps = false;
    
    public function producto(){
        return $this->belongsTo('App\PlnProductoIndicador','ide_producto_indicador');
    }
    
    public function region(){
        return $this->belongsTo('App\PlnProyectoRegion','ide_proyecto_region');
    }
    
    public function proyecto(){
        return $this->belongsTo('App\CfgProyecto','ide_proyecto');
    }
    
    public function detalle(){
        return $this->hasMany('App\PlnRegionProductoDetalle', 'ide_region_producto');
    }
}
