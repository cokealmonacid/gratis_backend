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
    Route::post('/users/login/facebook', 'UsersController@loginFacebook');
    Route::post('/users', 'UsersController@create');


	/** Posts **/
    Route::get('/posts/public', 'PostsController@show');
    Route::get('/posts/{id}', 'PostsController@showDetail');
    Route::get('/posts/user/{user_id}', 'PostsController@showUserPosts');

    /** Password Reset **/
    Route::post('/password/create', 'PasswordResetController@create');
    Route::get('/password/find/{token}', 'PasswordResetController@find');
    Route::post('/password/reset', 'PasswordResetController@reset');

    /** Regions **/
    Route::get('/regions', 'RegionsController@index');

    /** Tags **/
    Route::get('/tags', 'TagsController@index');

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
    Route::put('/posts/state/{id}', 'PostsController@updateState');
    Route::get('/favourites', 'PostsController@favourites');
    Route::get('/posts', 'PostsController@showMyPosts');
    Route::get('/info', 'PostsController@infoForCreate');

    /** User Post */
    Route::post('users/post/like', 'UsersController@likePost');

    /** Reasons */
    Route::get('/reasons', 'ReportReasonsController@index');
    Route::post('/reasons', 'ReportReasonsController@store');
});

