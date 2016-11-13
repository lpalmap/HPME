<?php

namespace App;

class PlnColaboradorCuentaDetalle extends BaseModel
{
    protected $primaryKey = 'ide_colaborador_cuenta_detalle';
    protected $table = 'pln_colaborador_cuenta_detalle';
    protected $fillable = array('num_detalle','valor','ide_colaborador_cuenta');
    public $timestamps = false;
    
    public function colaborador(){
        return $this->belongsTo('App\PlnColaboradorCuenta','ide_colaborador_cuenta');
    } 
}