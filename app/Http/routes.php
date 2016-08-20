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

Route::get('/login','Login@login');
Route::post('/login','Login@auth');
Route::get('/logout','Login@logout');



Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'lpalma','password'=>  bcrypt('lpalma'),'nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado 222';
});