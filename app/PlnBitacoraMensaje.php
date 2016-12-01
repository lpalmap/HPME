<?php

namespace App;

class PlnBitacoraMensaje extends BaseModel
{
    protected $primaryKey = 'ide_bitacora_mensaje';
    protected $table = 'pln_bitacora_mensaje';
    protected $fillable = array('ide_usuario','mensaje','fecha','ide_bitacora_proyecto_region');
    public $timestamps = false;
}