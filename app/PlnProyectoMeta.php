<?php

namespace App;

class PlnProyectoMeta extends BaseModel
{
    protected $primaryKey = 'ide_proyecto_meta';
    protected $table = 'pln_proyecto_meta';
    protected $fillable = array('ide_proyecto','ide_meta','ind_obligatorio');
    public $timestamps = false;
    
    public function meta(){
        return $this->belongsTo('App\CfgMeta','ide_meta');
    }
}