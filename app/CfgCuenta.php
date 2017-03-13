<?php

namespace App;

class CfgCuenta extends BaseModel
{
    protected $primaryKey = 'ide_cuenta';
    protected $table = 'cfg_cuenta';
    protected $fillable = array('cuenta','nombre','descripcion','ind_consolidar','estado','ide_cuenta_padre','codigo_interno');
    public $timestamps = false;
    
    public function padre(){
        return $this->belongsTo('App\CfgCuenta', 'ide_cuenta_padre');     
    }
}