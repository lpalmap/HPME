<?php

namespace App;

class CfgProducto extends BaseModel
{
    protected $primaryKey = 'ide_producto';
    protected $table = 'cfg_producto';
    protected $fillable = array('nombre','descripcion','orden','requiere_comprobantes');
    public $timestamps = false;
}