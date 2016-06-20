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
Route::get('login', function () {
    return view('login');
});


// Route::get('jugador/crear', function () {
//     return view('jugadorc');
// });
// Route::get('jugador/editar', function () {
//     return view('jugadore');
// });
// Route::get('jugador/desactivar', function () {
//     return view('jugadord');
// });

// Route::get('equipo/crear', function () {
//     return view('equipoc');
// });
// Route::get('equipo/editar', function () {
//     return view('equipoe');
// });
// Route::get('equipo/desactivar', function () {
//     return view('equipod');
// });
//
// Route::get('torneo', function () {
//     return view('torneo');
// });


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

//Route::match(['get', 'head'], 'partido/crear', 'PartidoController@create')->name('partido.create');
//Route::post('partido', 'PartidoController@store')->name('partido.store');
