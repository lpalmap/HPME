<?php

namespace App;

class CatMeta extends BaseModel
{
    protected $primaryKey = 'ide_meta';
    protected $table = 'cat_meta';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}


