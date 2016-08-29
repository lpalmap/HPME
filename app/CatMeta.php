<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CatMeta extends BaseModel
{
    use Authenticatable, CanResetPassword;

    protected $primaryKey = 'ide_meta';
    protected $table = 'cat_meta';
    protected $fillable = array('nombre','descripcion');
    public $timestamps = false;
}


