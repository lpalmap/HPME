<?php

namespace App;

class SegPrivilegio extends BaseModel
{
    protected $primaryKey = 'ide_privilegio';
    protected $table = 'seg_privilegio';
    protected $fillable = array('descripcion','grupo_privilegio');
    public $timestamps = false;
}