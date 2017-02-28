<?php

namespace App;

class MonBitacoraPeriodo extends BaseModel
{
    protected $primaryKey ='ide_bitacora_periodo' ;
    protected $table = 'mon_bitacora_periodo';
    protected $fillable = array('estado','ide_periodo_region');
    public $timestamps = false;
    
    public function periodo(){
        return $this->belongsTo('App\MonPeriodoRegion','ide_periodo_region');
    }    
}