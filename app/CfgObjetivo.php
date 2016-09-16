<?php

namespace App;

class CfgObjetivo extends BaseModel
{
    protected $primaryKey = 'ide_objetivo';
    protected $table = 'cfg_objetivo';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}