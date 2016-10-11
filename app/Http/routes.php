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

/* Mantenimiento de objetivos */

Route::get('/objetivos',['uses' => 'Objetivos@index', 'middleware' => 'auth'])->name('objetivos');
Route::delete('/objetivos/{id}',['uses' => 'Objetivos@delete', 'middleware' => 'auth']);
Route::get('/objetivos/{id}',['uses' => 'Objetivos@retrive', 'middleware' => 'auth']);
Route::post('/objetivos',['uses' => 'Objetivos@add', 'middleware' => 'auth']);
Route::put('/objetivos/{id}',['uses' => 'Objetivos@update', 'middleware' => 'auth']);

/* Mantenimiento de recursos */

Route::get('/recursos',['uses' => 'Recursos@index', 'middleware' => 'auth'])->name('recursos');
Route::delete('/recursos/{id}',['uses' => 'Recursos@delete', 'middleware' => 'auth']);
Route::get('/recursos/{id}',['uses' => 'Recursos@retrive', 'middleware' => 'auth']);
Route::post('/recursos',['uses' => 'Recursos@add', 'middleware' => 'auth']);
Route::put('/recursos/{id}',['uses' => 'Recursos@update', 'middleware' => 'auth']);


/* Mantenimiento de afiliados */

Route::get('/afiliados',['uses' => 'Afiliados@index', 'middleware' => 'auth'])->name('afiliados');
Route::delete('/afiliados/{id}','Afiliados@delete');
Route::get('/afiliados/{id}','Afiliados@retrive');
Route::post('/afiliados','Afiliados@add');
Route::put('/afiliados/{id}','Afiliados@update');

/* Mantenimiento de roles */

Route::get('/roles',['uses' => 'Roles@index', 'middleware' => 'auth'])->name('roles');
Route::delete('/roles/{id}','Roles@delete');
Route::get('/roles/{id}','Roles@retrive');
Route::post('/roles','Roles@add');
Route::put('/roles/{id}','Roles@update');

/* Mantenimiento de areas de atencion */

Route::get('/areas',['uses' => 'Areas@index', 'middleware' => 'auth'])->name('areas');
Route::delete('/areas/{id}','Areas@delete');
Route::get('/areas/{id}','Areas@retrive');
Route::post('/areas','Areas@add');
Route::put('/areas/{id}','Areas@update');

/* Mantenimiento de areas de parametros */

Route::get('/parametros',['uses' => 'Parametros@index', 'middleware' => 'auth'])->name('parametros');
Route::delete('/parametros/{id}','Parametros@delete');
Route::get('/parametros/{id}','Parametros@retrive');
Route::post('/parametros','Parametros@add');
Route::put('/parametros/{id}','Parametros@update');

/* Mantenimiento de lista de valores */

Route::get('/listas',['uses' => 'Listas@index', 'middleware' => 'auth'])->name('listas');
Route::delete('/listas/{id}','Listas@delete');
Route::get('/listas/{id}','Listas@retrive');
Route::post('/listas','Listas@add');
Route::put('/listas/{id}','Listas@update');


/* Mantenimiento de productos */

Route::get('/productos',['uses' => 'Productos@index', 'middleware' => 'auth'])->name('productos');
Route::delete('/productos/{id}',['uses' => 'Productos@delete', 'middleware' => 'auth']);
Route::get('/productos/{id}',['uses' => 'Productos@retrive', 'middleware' => 'auth']);
Route::post('/productos',['uses' => 'Productos@add', 'middleware' => 'auth']);
Route::put('/productos/{id}',['uses' => 'Productos@update', 'middleware' => 'auth']);


/*
 *
 * Planificacion 
 * 
 *  */
//Planificacion anual
Route::get('/planificacion_anual',['uses' => 'ProyectoPlanificacion@index', 'middleware' => 'auth'])->name('planificacion_anual');
Route::post('/planificacion_anual',['uses' => 'ProyectoPlanificacion@addPlantilla', 'middleware' => 'auth']);

//Metas
Route::get('/plantilla/{id}',['uses' => 'ProyectoPlanificacion@metasProyecto', 'middleware' => 'auth']);
//Borrar meta
Route::delete('/planmeta/{id}',['uses' => 'ProyectoPlanificacion@deleteMeta', 'middleware' => 'auth']);
//Agregar meta
Route::post('/planmeta',['uses' => 'ProyectoPlanificacion@addMeta', 'middleware' => 'auth']);
//Actualizar meta
Route::put('/planmeta/{id}',['uses' => 'ProyectoPlanificacion@updateMeta', 'middleware' => 'auth']);
//Lista metas
Route::post('/planmeta/all',['uses' => 'ProyectoPlanificacion@retriveAllMetas', 'middleware' => 'auth']);


//Objetivos
Route::get('/meta/{id}',['uses' => 'ProyectoPlanificacion@objetivoMeta', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planobjetivo',['uses' => 'ProyectoPlanificacion@addObjetivo', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planobjetivo/all',['uses' => 'ProyectoPlanificacion@retriveAllObjetivos', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planobjetivo/{id}',['uses' => 'ProyectoPlanificacion@deleteObjetivo', 'middleware' => 'auth']);



//Area atencion
//Objetivos
Route::get('/objetivo/{id}',['uses' => 'ProyectoPlanificacion@areaObjetivo', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planarea',['uses' => 'ProyectoPlanificacion@addArea', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planarea/all',['uses' => 'ProyectoPlanificacion@retriveAllAreas', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planarea/{id}',['uses' => 'ProyectoPlanificacion@deleteArea', 'middleware' => 'auth']);



//Route::get('/proyectos',['uses' => 'Proyectos@index', 'middleware' => 'auth'])->name('proyectos');


Route::get('/planificacion_objetivos',['uses' => 'ProyectoPlanificacion@objetivos', 'middleware' => 'auth']);
Route::get('/planificacion_areas',['uses' => 'ProyectoPlanificacion@areas', 'middleware' => 'auth']);
Route::get('/planificacion_indicadores',['uses' => 'ProyectoPlanificacion@indicadores', 'middleware' => 'auth']);
Route::get('/planificacion_productos',['uses' => 'ProyectoPlanificacion@productos', 'middleware' => 'auth']);







Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'root','password'=>  bcrypt('root'),'nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado 222';
});