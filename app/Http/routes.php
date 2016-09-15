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
Route::delete('/metas/{id}',['uses' => 'Metas@delete', 'middleware' => 'auth']);
Route::get('/metas/{id}',['uses' => 'Metas@retrive', 'middleware' => 'auth']);
Route::post('/metas',['uses' => 'Metas@add', 'middleware' => 'auth']);
Route::put('/metas/{id}',['uses' => 'Metas@update', 'middleware' => 'auth']);

/* Mantenimiento de regiones */

Route::get('/regiones',['uses' => 'Regiones@index', 'middleware' => 'auth'])->name('regiones');
Route::delete('/regiones/{id}',['uses' => 'Regiones@delete', 'middleware' => 'auth']);
Route::get('/regiones/{id}',['uses' => 'Regiones@retrive', 'middleware' => 'auth']);
Route::post('/regiones',['uses' => 'Regiones@add', 'middleware' => 'auth']);
Route::put('/regiones/{id}',['uses' => 'Regiones@update', 'middleware' => 'auth']);

/* Mantenimiento de proyectos */

Route::get('/proyectos',['uses' => 'Proyectos@index', 'middleware' => 'auth'])->name('proyectos');
Route::delete('/proyectos/{id}',['uses' => 'Proyectos@delete', 'middleware' => 'auth']);
Route::get('/proyectos/{id}',['uses' => 'Proyectos@retrive', 'middleware' => 'auth']);
Route::post('/proyectos',['uses' => 'Proyectos@add', 'middleware' => 'auth']);
Route::put('/proyectos/{id}',['uses' => 'Proyectos@update', 'middleware' => 'auth']);


/* Mantenimiento de indicadores */

Route::get('/indicadores',['uses' => 'Indicadores@index', 'middleware' => 'auth'])->name('indicadores');
Route::delete('/indicadores/{id}',['uses' => 'Indicadores@delete', 'middleware' => 'auth']);
Route::get('/indicadores/{id}',['uses' => 'Indicadores@retrive', 'middleware' => 'auth']);
Route::post('/indicadores',['uses' => 'Indicadores@add', 'middleware' => 'auth']);
Route::put('/indicadores/{id}',['uses' => 'Indicadores@update', 'middleware' => 'auth']);

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