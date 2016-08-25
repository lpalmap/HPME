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
Route::get('/usuarios','Usuarios@index')->name('usuarios');
Route::delete('/usuarios/{id}','Usuarios@delete');
Route::get('/usuarios/{id}','Usuarios@retrive');
Route::post('/usuarios','Usuarios@add');
Route::put('/usuarios/{id}','Usuarios@update');



Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'root','password'=>  bcrypt('root'),'nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado 222';
});