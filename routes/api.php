<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$controller_path = 'App\Http\Controllers';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/ixp/table', $controller_path . '\ixp\IxpController@index');
Route::post('/ixp/table', $controller_path . '\ixp\IxpController@store');
Route::delete('/ixp/table/deleteall', $controller_path . '\ixp\IxpController@deleteAll');
Route::delete('/ixp/table/{id}', $controller_path . '\ixp\IxpController@destroy');
