<?php

namespace App;

class CfgRecurso extends BaseModel
{
    protected $primaryKey = 'ide_recurso';
    protected $table = 'cfg_recurso';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}