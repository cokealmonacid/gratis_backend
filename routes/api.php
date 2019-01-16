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
	Route::get('/users/login/redirect/facebook', 'UsersController@redirectToProvider');
    Route::get('/users/login/facebook', 'UsersController@handleProviderCallback');
    Route::post('/users', 'UsersController@create');


	/** Posts **/

    Route::get('/posts/public', 'PostsController@show');
    Route::get('/posts/{id}', 'PostsController@showDetail');
    Route::get('/posts/user/{post_id}/{user_id}', 'PostsController@showUserPosts');

});

Route::group(['prefix' => 'v1','middleware'=>['auth:api'] ,'namespace' => 'API'], function() {
    /** Users  */
    Route::post('/users/logout', 'UsersController@logout');
    Route::get('/users', 'UsersController@show');
    Route::put('/users', 'UsersController@update');
    Route::put('/users/avatar', 'UsersController@updateAvatar');

    /** Posts **/
    Route::post('/posts', 'PostsController@store');
    Route::put('/posts/{id}', 'PostsController@update');
    Route::get('/favourites', 'PostsController@favourites');

    /** User Post */
    Route::post('users/post/like', 'UsersController@likePost');
});

