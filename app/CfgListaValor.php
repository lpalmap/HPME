<?php

namespace App;

class CfgListaValor extends BaseModel
{
    protected $primaryKey = 'ide_lista';
    protected $table = 'cfg_lista_valor';
    protected $fillable = array('grupo_lista','codigo_lista','descripcion');
    public $timestamps = false;
}