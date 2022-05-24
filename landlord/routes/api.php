<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
 

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::post("test",[Api::class,'test']);

Route::group(['middleware' => 'api'], function($routes) {
    Route::post("register",[Api::class,'register']);
    Route::post("login",[Api::class,'login']);
    Route::post("test",[Api::class,'test']);
    Route::post("profile",[Api::class,'profile']);
    Route::post("refresh",[Api::class,'refresh']);
    Route::post("property_type",[Api::class,'property_type']);
    Route::post("addProperty",[Api::class,'addProperty']);
    Route::post("propertyList",[Api::class,'propertyList']);


});

// Route::group([
//     'middleware' => 'api',
//     'namespace' => 'App\Http\Controllers',    
// ], function($router) {
//     Route::resource("todos",[Api::class,'register']);
//     Route::post("login",[Api::class,'login']);
// });
