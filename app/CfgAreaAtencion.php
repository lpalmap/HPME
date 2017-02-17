<?php

namespace App;

class CfgAreaAtencion extends BaseModel
{
    protected $primaryKey = 'ide_area';
    protected $table = 'cfg_area_atencion';
    protected $fillable = array('nombre','descripcion','orden','orden_especial');
    public $timestamps = false;
}