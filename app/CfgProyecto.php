<?php

namespace App;

class CfgProyecto extends BaseModel
{
    protected $primaryKey = 'ide_proyecto';
    protected $table = 'cfg_proyecto';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}