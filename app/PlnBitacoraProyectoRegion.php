<?php

namespace App;

class PlnBitacoraProyectoRegion extends BaseModel
{
    protected $primaryKey = 'ide_bitacora_proyecto_region';
    protected $table = 'pln_bitacora_proyecto_region';
    protected $fillable = array('ide_proyecto_region','estado');
    public $timestamps = false;
}