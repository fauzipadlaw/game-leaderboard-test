<?php

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthController@login')->name('login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');
    Route::post('register', 'Api\AuthController@register');
    Route::post('payload', 'Api\AuthController@payload');
});

Route::group([
    'middleware' => [
        'auth:api',
        'throttle:40,1' //limit rate request limit per user 40 hits per 60 seconds
    ]
], function () {
    // game's routes
    Route::get('games', 'Api\GameController@index');
    Route::get('games/all', 'Api\GameController@all');
    Route::get('games/sort', 'Api\GameController@sortByMostPlayedGames');
    Route::get('games/deleted', 'Api\GameController@deletedGames');
    Route::post('games', 'Api\GameController@add');
    Route::delete('games/purge-deleted', 'Api\GameController@purgeAll');
    Route::post('games/{id}', 'Api\GameController@update');
    Route::delete('games/{id}', 'Api\GameController@delete');

    // player's routes
    Route::get('players', 'Api\PlayerController@index');
    Route::post('players', 'Api\PlayerController@add');
    Route::get('players/deleted', 'Api\PlayerController@deletedPlayers');
    Route::delete('players/purge-deleted', 'Api\PlayerController@purgeAll');
    Route::post('players/{id}', 'Api\PlayerController@update');
    Route::delete('players/{id}', 'Api\PlayerController@delete');
});
