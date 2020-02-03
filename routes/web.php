<?php

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

Route::group(['prefix' => 'api'], function () {
    Route::get('/getList', 'PokemonController@getList');
    Route::get('/getDetail/{s}', 'PokemonController@getDetail');
    Route::post('/catchPokemon', 'PokemonController@catchPokemon');
    Route::get('/dataPaginate', 'PokemonController@dataPaginate');

    Route::get('/myPokemonPaginate', 'PokemonController@getMyPokemonPaginate');
    Route::get('/getDetailMyPokemon/{s}', 'PokemonController@getDetailMyPokemon');
    Route::get('/releasePokemon/{s}', 'PokemonController@releasePokemon');
});


Route::get('/{any}', 'PokemonController@index')->where('any', '.*');


