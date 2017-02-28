<?php

namespace App;

class MonBitacoraPeriodoMensaje extends BaseModel
{
    protected $primaryKey ='ide_bitacora_mensaje' ;
    protected $table = 'mon_bitacora_periodo_mensaje';
    protected $fillable = array('fecha','mensaje','ide_bitacora_periodo','ide_usuario');
    public $timestamps = false;
    
    public function bitacora(){
        return $this->belongsTo('App\MonBitacoraPeriodo','ide_bitacora_periodo');
    }
    
    public function usuario(){
        return $this->belongsTo('App\SegUsuario','ide_usuario');
    }
}