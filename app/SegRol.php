<?php

namespace App;

class SegRol extends BaseModel
{
    protected $primaryKey = 'ide_rol';
    protected $table = 'seg_rol';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}