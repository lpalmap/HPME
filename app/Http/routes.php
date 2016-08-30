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

Route::get('/login/{error?}','Login@login')->name('login');
Route::post('/login','Login@auth');
Route::get('/logout','Login@logout');
Route::get('/home',['uses' => 'Home@index', 'middleware' => 'auth'])->name('home');

/* Mantenimiento de usuarios */

Route::get('/usuarios','Usuarios@index')->name('usuarios');
Route::delete('/usuarios/{id}','Usuarios@delete');
Route::get('/usuarios/{id}','Usuarios@retrive');
Route::post('/usuarios','Usuarios@add');
Route::put('/usuarios/{id}','Usuarios@update');

/* Mantenimiento de metas */

Route::get('/metas',['uses' => 'Metas@index', 'middleware' => 'auth'])->name('metas');
Route::delete('/metas/{id}','Metas@delete');
Route::get('/metas/{id}','Metas@retrive');
Route::post('/metas','Metas@add');
Route::put('/metas/{id}','Metas@update');

/* Mantenimiento de regiones */

Route::get('/regiones',['uses' => 'Regiones@index', 'middleware' => 'auth'])->name('regiones');
Route::delete('/regiones/{id}','Regiones@delete');
Route::get('/regiones/{id}','Regiones@retrive');
Route::post('/regiones','Regiones@add');
Route::put('/regiones/{id}','Regiones@update');

/* Mantenimiento de afiliados */

Route::get('/afiliados',['uses' => 'Afiliados@index', 'middleware' => 'auth'])->name('afiliados');
Route::delete('/afiliados/{id}','Afiliados@delete');
Route::get('/afiliados/{id}','Afiliados@retrive');
Route::post('/afiliados','Afiliados@add');
Route::put('/afiliados/{id}','Afiliados@update');





Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'root','password'=>  bcrypt('root'),'nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado 222';
});