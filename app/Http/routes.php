<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/{name}','Login@index');

Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'test','nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado';
});