<?php

namespace App;

class CfgIndicador extends BaseModel
{
    protected $primaryKey = 'ide_indicador';
    protected $table = 'cfg_indicador';
    protected $fillable = array('nombre','descripcion','orden');
    public $timestamps = false;
}