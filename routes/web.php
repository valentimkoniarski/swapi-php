<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('planetas', 'App\Http\Controllers\PlanetasController@listaDePlanetas');
Route::get('planetas/{planeta_id}', 'App\Http\Controllers\PlanetasController@listaDePlanetaDetalhes');
Route::get('planetas/filme/{planeta_id}', 'App\Http\Controllers\PlanetasController@listaDePlanetasFilmes');
Route::get('planetas/personagem/{planeta_id}', 'App\Http\Controllers\PlanetasController@listaDePlanetasPersonagens');

Route::get('personagens', 'App\Http\Controllers\PersonagensController@listaDePersonagens');
Route::get('filmes', 'App\Http\Controllers\FilmesController@listaDeFilmes');
