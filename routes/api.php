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

Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});
Route::get('/test', 'HomeController@test');
Route::get('/emp/{id}', 'HomeController@empEdit');
Route::get('/emp/update/{id}/{bounce}', 'HomeController@empupdate');
Route::get('calculate/result', 'HomeController@calculateResult');
Route::get('calculate/result/{monthNum}', 'HomeController@showMonth');
