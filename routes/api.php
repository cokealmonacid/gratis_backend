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

Route::group(['prefix' => 'v1', 'namespace' => 'API'], function() {
	/** Users **/
    Route::post('/users/login', 'UsersController@login');
    Route::post('/users', 'UsersController@create');

	/** Posts **/
    Route::get('/posts/public', 'PostsController@index');
});

Route::group(['prefix' => 'v1','middleware'=>['auth:api'] ,'namespace' => 'API'], function() {
    Route::post('/users/logout', 'UsersController@logout');

    Route::post('/posts', 'PostsController@store');
});

