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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/unauthenticated', function(Request $request){
    return response()->json(["message"=>"Unauthenticated"], 401);
})->name('unauthenticated');

Route::post('authenticate','ApiTokenController@authenticate');

Route::middleware('auth:api')->group(function () {
    Route::resource('usuarios','UsuariosController');
    Route::group(['namespace'=>'Seguridad'], function($router){
        Route::resource('permisos','PermisosController');
        Route::resource('roles','RolesController');
        Route::resource('grupos-permisos','GruposPermisosController');
    });
    
});

