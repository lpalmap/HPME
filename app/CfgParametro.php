<?php

namespace App;

class CfgParametro extends BaseModel
{
    protected $primaryKey = 'ide_parametro';
    protected $table = 'cfg_parametro';
    protected $fillable = array('nombre','valor','descripcion');
    public $timestamps = false;
}