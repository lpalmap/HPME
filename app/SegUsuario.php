<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class SegUsuario extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $primaryKey = 'ide_usuario';
    protected $table = 'seg_usuario';
    protected $fillable = array('usuario','password', 'nombres', 'apellidos','ide_afiliado');
    public $timestamps = false;
    
    public function roles(){
        return $this->belongsToMany('App\SegRol', 'seg_usuario_rol', 'ide_usuario', 'ide_rol');     
    }
}
