<?php

namespace App;

class PlnColaboradorCuenta extends BaseModel
{
    protected $primaryKey = 'ide_colaborador_cuenta';
    protected $table = 'pln_colaborador_cuenta';
    protected $fillable = array('ide_cuenta','ide_presupuesto_colaborador');
    public $timestamps = false;
    
    public function colaborador(){
        return $this->belongsTo('App\CfgPresupuestoColaborador','ide_presupuesto_colaborador');
    }
    
    public function cuenta(){
        return $this->belongsTo('App\CfgCuenta','ide_cuenta');
    }
    
    public function detalle(){
        return $this->hasMany('App\PlnColaboradorCuentaDetalle','ide_colaborador_cuenta');
    }
    
}