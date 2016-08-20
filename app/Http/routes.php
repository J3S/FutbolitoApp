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
    if(Auth::check())
        return view('welcome');
    else
        return view('login');
 });
Route::get('login', ['as' => 'login', function () {
    return view('login');
}]);

Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

//-------------------Branny Rutas-----------------------
//rutas RESTfull para jugador, ejecutar (php artisan route:list)
Route::resource('equipo', 'EquipoController');
Route::post('equipo/search', 'EquipoController@search')->name('equipo.search');
// Route::get('equipo/{categoria}', 'EquipoController@showJugadoresCatergoria')->name('equipo.showJugadores');
Route::get('jugadores/{categoria}', 'EquipoController@getJugadoresCategoria');
//-------------------Branny Rutas-----------------------


//---------------- rutas de Jugador -------------------------------------
/*Route::post('jugador', 'JugadorController@store')->name('jugador.store');
Route::match(['get', 'head'], 'jugador/crear', 'JugadorController@create')->name('jugador.create');

Route::put('jugador/{jugador}', 'JugadorController@update')->name('jugador.update');
Route::match(['get', 'head'], 'jugador/{jugador}/edit', 'JugadorController@edit')->name('jugador.edit');*/

Route::resource('jugador', 'JugadorController',
	['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);

Route::post('selectJugador', 'JugadorController@searchJugador');


Route::resource('torneo', 'TorneoController',
    ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);

Route::post('buscarTorneos', 'TorneoController@searchTorneo');

Route::resource('partido', 'PartidoController',
	['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);

Route::post('selectPartido', 'PartidoController@searchPartido');

Route::get('get_jugador/{id}', 'ResourceController@getJugador');
Route::get('get_jugadores', 'ResourceController@getJugadores');
Route::get('get_equipo/{id}', 'ResourceController@getEquipo');
Route::get('get_equipos', 'ResourceController@getEquipos');
Route::get('get_torneo/{id}', 'ResourceController@getTorneo');
Route::get('get_torneos', 'ResourceController@getTorneos');
Route::get('get_partido/{id}', 'ResourceController@getPartido');
Route::get('get_partidos', 'ResourceController@getPartidos');
Route::get('get_torneoequipo/{id}', 'ResourceController@getTorneoEquipo');
Route::get('get_torneoequipos', 'ResourceController@getTorneoEquipos');
Route::get('get_categoria/{id}', 'ResourceController@getCategoria');
Route::get('get_categorias', 'ResourceController@getCategorias');
Route::get('get_aniotorneos/{anio}', 'ResourceController@getAnioTorneos');
Route::get('get_anioscontorneos', 'ResourceController@getAniosConTorneos');
Route::get('get_jornada/{torneo}/{jornada}', 'ResourceController@getPartidosJornada');
Route::get('get_tablaposiciones/{id}', 'ResourceController@getTablaPosicionesTorneo');
Route::get('get_tablasposicionesanio/{anio}', 'ResourceController@getTablasPosicionesAnio');
Route::get('get_partidostorneo/{id}', 'ResourceController@getPartidosTorneo');
Route::get('get_ult10partidosequipo/{id}', 'ResourceController@getUltimos10PartidosEquipo');
Route::get('get_jugadores_equipo/{id}', 'ResourceController@getJugadoresEquipo');
Route::get('get_ultima_participacion/{id}', 'ResourceController@getUltimaTablaEquipo');
