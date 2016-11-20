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

/* Perfil de usuario */
Route::get('/perfil',['uses' => 'Perfil@index', 'middleware' => 'auth'])->name('perfil');
Route::post('/perfil/update',['uses' => 'Perfil@update', 'middleware' => 'auth']);
/* Mantenimiento de usuarios */

Route::get('/usuarios',['uses' => 'Usuarios@index', 'middleware' => 'auth'])->name('usuarios');
Route::delete('/usuarios/{id}',['uses' => 'Usuarios@delete', 'middleware' => 'auth']);
Route::get('/usuarios/{id}',['uses' => 'Usuarios@retrive', 'middleware' => 'auth']);
Route::post('/usuarios',['uses' => 'Usuarios@add', 'middleware' => 'auth']);
Route::put('/usuarios/{id}',['uses' => 'Usuarios@update', 'middleware' => 'auth']);

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
Route::post('/regiones/admin',['uses' => 'Regiones@retriveAllAdmin', 'middleware' => 'auth']);

/* Mantenimiento de proyectos */

Route::get('/proyectos',['uses' => 'Proyectos@index', 'middleware' => 'auth'])->name('proyectos');
Route::delete('/proyectos/{id}',['uses' => 'Proyectos@delete', 'middleware' => 'auth']);
Route::get('/proyectos/{id}',['uses' => 'Proyectos@retrive', 'middleware' => 'auth']);
Route::post('/proyectos',['uses' => 'Proyectos@add', 'middleware' => 'auth']);
Route::put('/proyectos/{id}',['uses' => 'Proyectos@update', 'middleware' => 'auth']);
Route::post('/proyectos/regiones',['uses' => 'Proyectos@retriveAllRegiones', 'middleware' => 'auth']);

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

/* Mantenimiento departamentos */

Route::get('/departamentos',['uses' => 'Departamentos@index', 'middleware' => 'auth'])->name('departamentos');
Route::delete('/departamentos/{id}',['uses' => 'Departamentos@delete', 'middleware' => 'auth']);
Route::get('/departamentos/{id}',['uses' => 'Departamentos@retrive', 'middleware' => 'auth']);
Route::post('/departamentos',['uses' => 'Departamentos@add', 'middleware' => 'auth']);
Route::put('/departamentos/{id}',['uses' => 'Departamentos@update', 'middleware' => 'auth']);
Route::post('/departamentos/admin',['uses' => 'Departamentos@retriveAllAdmin', 'middleware' => 'auth']);

/*  Mantenimiento de colaboradores */

Route::get('/colaboradores',['uses' => 'Colaboradores@index', 'middleware' => 'auth'])->name('colaboradores');
Route::delete('/colaboradores/{id}',['uses' => 'Colaboradores@delete', 'middleware' => 'auth']);
Route::get('/colaboradores/{id}',['uses' => 'Colaboradores@retrive', 'middleware' => 'auth']);
Route::post('/colaboradores',['uses' => 'Colaboradores@add', 'middleware' => 'auth']);
Route::put('/colaboradores/{id}',['uses' => 'Colaboradores@update', 'middleware' => 'auth']);
Route::post('/colaboradores/admin',['uses' => 'Colaboradores@retriveAllAdmin', 'middleware' => 'auth']);

/* Mantenimiento de cuentas */

Route::get('/cuentas/{id?}',['uses' => 'Cuentas@index', 'middleware' => 'auth'])->name('colaboradores')->name('cuentas');
Route::delete('/cuenta/{id}',['uses' => 'Cuentas@delete', 'middleware' => 'auth']);
Route::get('/cuenta/{id}',['uses' => 'Cuentas@retrive', 'middleware' => 'auth']);
Route::post('/cuenta',['uses' => 'Cuentas@add', 'middleware' => 'auth']);
Route::put('/cuenta/{id}',['uses' => 'Cuentas@update', 'middleware' => 'auth']);

/*
 *
 * Planificacion 
 * 
 *  */
//Plantilla
Route::get('/proyecto',['uses' => 'PlantillaPlanificacion@index', 'middleware' => 'auth']);
//Obtiene el proyecto
Route::get('/planproyecto/{id}',['uses' => 'PlantillaPlanificacion@retrivePlantilla', 'middleware' => 'auth']);
//borrar plantilla
Route::delete('/planproyecto/{id}',['uses' => 'PlantillaPlanificacion@deletePlantilla', 'middleware' => 'auth']);
//agrega plantilla
Route::post('/planproyecto',['uses' => 'PlantillaPlanificacion@addPlantilla', 'middleware' => 'auth']);
//actualiza plantilla
Route::put('/planproyecto/{id}',['uses' => 'PlantillaPlanificacion@updatePlantilla', 'middleware' => 'auth']);




//Metas
Route::get('/plantilla/{id}',['uses' => 'PlanificacionMeta@metasProyecto', 'middleware' => 'auth']);
//Borrar meta
Route::delete('/planmeta/{id}',['uses' => 'PlanificacionMeta@deleteMeta', 'middleware' => 'auth']);
//Agregar meta
Route::post('/planmeta',['uses' => 'PlanificacionMeta@addMeta', 'middleware' => 'auth']);
//Actualizar meta
Route::put('/planmeta/{id}',['uses' => 'PlanificacionMeta@updateMeta', 'middleware' => 'auth']);
//Lista metas
Route::post('/planmeta/all',['uses' => 'PlanificacionMeta@retriveAllMetas', 'middleware' => 'auth']);


//Objetivos
Route::get('/meta/{id}',['uses' => 'PlanificacionObjetivo@objetivoMeta', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planobjetivo',['uses' => 'PlanificacionObjetivo@addObjetivo', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planobjetivo/all',['uses' => 'PlanificacionObjetivo@retriveAllObjetivos', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planobjetivo/{id}',['uses' => 'PlanificacionObjetivo@deleteObjetivo', 'middleware' => 'auth']);



//Area atencion
Route::get('/objetivo/{id}',['uses' => 'PlanificacionArea@areaObjetivo', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planarea',['uses' => 'PlanificacionArea@addArea', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planarea/all',['uses' => 'PlanificacionArea@retriveAllAreas', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planarea/{id}',['uses' => 'PlanificacionArea@deleteArea', 'middleware' => 'auth']);


//Indicadores
Route::get('/area/{id}',['uses' => 'PlanificacionIndicador@indicadorArea', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planindicador',['uses' => 'PlanificacionIndicador@addIndicador', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planindicador/all',['uses' => 'PlanificacionIndicador@retriveAllIndicadores', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planindicador/{id}',['uses' => 'PlanificacionIndicador@deleteIndicador', 'middleware' => 'auth']);

//productos
Route::get('/indicador/{id}',['uses' => 'PlanificacionProducto@productoArea', 'middleware' => 'auth']);
//Agregar objetivos
Route::post('/planproducto',['uses' => 'PlanificacionProducto@addProducto', 'middleware' => 'auth']);
//Lista objetivos
Route::post('/planproducto/all',['uses' => 'PlanificacionProducto@retriveAllProductos', 'middleware' => 'auth']);
//Borrar el objetivo
Route::delete('/planproducto/{id}',['uses' => 'PlanificacionProducto@deleteProducto', 'middleware' => 'auth']);


//Agrega/actualiza la planificación de un producto para la región de la que es administrador el usuario logueado.
Route::post('/planproducto/addDetalle',['uses' => 'PlanificacionProducto@addDetalle', 'middleware' => 'auth']);
//Obtiene la planificacion para un producto.
Route::post('/planproducto/retriveDetalle',['uses' => 'PlanificacionProducto@retriveDetalle', 'middleware' => 'auth']);

//Route::get('/proyectos',['uses' => 'Proyectos@index', 'middleware' => 'auth'])->name('proyectos');


//Route::get('/planificacion_objetivos',['uses' => 'ProyectoPlanificacion@objetivos', 'middleware' => 'auth']);
//Route::get('/planificacion_areas',['uses' => 'ProyectoPlanificacion@areas', 'middleware' => 'auth']);
//Route::get('/planificacion_indicadores',['uses' => 'ProyectoPlanificacion@indicadores', 'middleware' => 'auth']);
//Route::get('/planificacion_productos',['uses' => 'ProyectoPlanificacion@productos', 'middleware' => 'auth']);


//Lista de planificaciones por regiones
Route::get('/planificaciones',['uses' => 'PlanificacionRegion@planificacionRegion', 'middleware' => 'auth']);
Route::get('/plandetalle/{id}',['uses' => 'PlanificacionRegion@planificacionRegionDetalle', 'middleware' => 'auth']);
Route::get('/planconsolidado/{id}',['uses' => 'PlanificacionRegion@planificacionConsolidada', 'middleware' => 'auth']);

/*   PRESUPUESTO    */
Route::get('/presupuestos',['uses' => 'ProyectoPresupuesto@index', 'middleware' => 'auth']);
Route::get('/presupuestos/{id}',['uses' => 'ProyectoPresupuesto@retriveDepartamentos', 'middleware' => 'auth']);
Route::get('/departamento/{id}',['uses' => 'ProyectoPresupuesto@retriveColaboradores', 'middleware' => 'auth']);
Route::post('/departamento/all',['uses' => 'ProyectoPresupuesto@retriveAllColaboradores', 'middleware' => 'auth']);
Route::put('/departamento/colaborador',['uses' => 'ProyectoPresupuesto@addColaborador', 'middleware' => 'auth']);
Route::delete('/departamento/{id}',['uses' => 'ProyectoPresupuesto@deleteColaborador', 'middleware' => 'auth']);

//Presupuesto cuentas
Route::get('/colaborador/{colaborador}/cuenta/{id?}',['uses' => 'ProyectoPresupuesto@colaboradorCuenta', 'middleware' => 'auth']);

Route::post('/cuenta/addDetalle',['uses' => 'ProyectoPresupuesto@addDetalleCuenta', 'middleware' => 'auth']);
Route::post('/cuenta/getDetalle',['uses' => 'ProyectoPresupuesto@getDetalleCuenta', 'middleware' => 'auth']);

//Presupuesto consolidado 
Route::get('/presupuestocolaborador/{id}',['uses' => 'PresupuestoConsolidado@consolidadoColaborador', 'middleware' => 'auth']);
//Route::get('/plandetalle/{id}',['uses' => 'PlanificacionRegion@planificacionRegionDetalle', 'middleware' => 'auth']);
//Route::get('/planconsolidado/{id}',['uses' => 'PlanificacionRegion@planificacionConsolidada', 'middleware' => 'auth']);


Route::get('/insert', function() {
    App\SegUsuario::create(array('usuario' => 'root','password'=>  bcrypt('root'),'nombres'=>'Luis Antonio','apellidos'=>'Palma Pineda','ide_afiliado'=>null));
    return 'Usuario Agregado 222';
});