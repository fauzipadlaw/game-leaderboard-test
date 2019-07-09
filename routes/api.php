<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthController@login')->name('login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');
    Route::post('register', 'Api\AuthController@register');
});

Route::group([
    'middleware' => 'api'
], function () {
    // game's routes
    Route::get('games', 'Api\GameController@index');
    Route::get('games/sort', 'Api\GameController@sortByMostPlayedGames');
    Route::post('games', 'Api\GameController@add');
    Route::delete('games/purge-deleted', 'Api\GameController@purgeAll');
    Route::post('games/{id}', 'Api\GameController@update');
    Route::delete('games/{id}', 'Api\GameController@delete');

    // player's routes
    Route::get('players', 'Api\PlayerController@index');
    Route::post('players', 'Api\PlayerController@add');
    Route::delete('players/purge-deleted', 'Api\PlayerController@purgeAll');
    Route::post('players/{id}', 'Api\PlayerController@update');
    Route::delete('players/{id}', 'Api\PlayerController@delete');
});
