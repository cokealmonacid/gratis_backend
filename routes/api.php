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
    Route::post('/users/login', 'UsersController@login');
});


Route::group(['prefix' => 'v1', 'namespace' => 'API'], function() {
    Route::get('/posts/public', 'PostsController@getPublicPosts');
});