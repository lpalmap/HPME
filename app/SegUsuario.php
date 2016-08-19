<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegUsuario extends BaseModel
{
    protected $primaryKey = 'ide_usuario';
    protected $table = 'seg_usuario';
    protected $fillable = array('usuario', 'nombres', 'apellidos','ide_afiliado');
    public $timestamps = false;
}
