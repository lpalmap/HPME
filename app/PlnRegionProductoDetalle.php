<?php

namespace App;

class PlnRegionProductoDetalle extends BaseModel
{
    protected $primaryKey = 'ide_region_producto_detalle';
    protected $table = 'pln_region_producto_detalle';
    protected $fillable = array('ide_region_producto','num_detalle','valor');
    public $timestamps = false;
    
    public function producto(){
        return $this->belongsTo('App\PlnRegionProducto','ide_region_producto');
    }
}
