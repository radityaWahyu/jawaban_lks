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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/register','UserController@register');
Route::post('/login','UserController@login');

Route::group(['middleware'=>['auth:api']], function(){
    Route::post('/event','EventController@create');
    Route::post('/event/update/{id}','EventController@update');
    Route::get('/event/delete/{id}','EventController@destroy');
    Route::get('/event','EventController@index');
    Route::post('/event/join','EventController@join');
    Route::post('/event/user/{id}','UserController@getUser');
    Route::post('/event/member/{id}','EventController@getEvent');
});