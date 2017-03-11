<?php

namespace App;

class MonArchivoProductoPeriodo extends BaseModel
{
    protected $primaryKey ='ide_archivo_producto' ;
    protected $table = 'mon_archivo_producto_periodo';
    protected $fillable = array('nombre','fecha','ide_region_producto_detalle');
    public $timestamps = false;
    
    public function detalle(){
        return $this->belongsTo('App\PlnRegionProductoDetalle','ide_region_producto_detalle');
    }    
}