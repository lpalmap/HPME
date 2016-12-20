<?php

namespace App;

class CfgPuesto extends BaseModel
{
    protected $primaryKey = 'ide_puesto';
    protected $table = 'cfg_puesto';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}