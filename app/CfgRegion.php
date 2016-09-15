<?php

namespace App;

class CfgRegion extends BaseModel
{
    protected $primaryKey = 'ide_region';
    protected $table = 'cfg_region';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}