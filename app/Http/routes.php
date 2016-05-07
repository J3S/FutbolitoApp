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
Route::get('jugador/crear', function () {
    return view('jugadorc');
});
Route::get('jugador/editar', function () {
    return view('jugadore');
});
Route::get('jugador/desactivar', function () {
    return view('jugadord');
});
