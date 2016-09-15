<?php

namespace App;

class CfgMeta extends BaseModel
{
    protected $primaryKey = 'ide_meta';
    protected $table = 'cfg_meta';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}

