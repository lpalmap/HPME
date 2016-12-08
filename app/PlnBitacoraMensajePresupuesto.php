<?php

namespace App;

class PlnBitacoraMensajePresupuesto extends BaseModel
{
    protected $primaryKey = 'ide_bitacora_mensaje';
    protected $table = 'pln_bitacora_mensaje_presupuesto';
    protected $fillable = array('ide_usuario','mensaje','fecha','ide_bitacora_presupuesto');
    public $timestamps = false;
    
    public function usuario(){
        return $this->belongsTo('App\SegUsuario','ide_usuario');
    }
}