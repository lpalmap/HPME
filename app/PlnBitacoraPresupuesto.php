<?php

namespace App;

class PlnBitacoraPresupuesto extends BaseModel
{
    protected $primaryKey = 'ide_bitacora_presupuesto';
    protected $table = 'pln_bitacora_presupuesto';
    protected $fillable = array('ide_presupuesto_departamento','estado');
    public $timestamps = false;
}