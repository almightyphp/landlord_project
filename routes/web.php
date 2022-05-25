<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Pricerange;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('login/login');
// });
// Route::get('/', 'logincontroller@index');


Route::get('/', [LoginController::class, 'index']);
// Route::post('/loginsubmit',[LoginController::class, 'loginCheck']);
Route::post('/loginsubmit', 'App\Http\Controllers\LoginController@loginCheck')->name('loginsubmit.post');
Route::get('/logout', 'App\Http\Controllers\LoginController@logout')->name('logout.post');


Route::group(['middleware'=>['sessioncheck']], function(){
    Route::get('/dashboard',[LoginController::class, 'dashboard']);
    Route::get('/propertytype', 'App\Http\Controllers\Propertytype@index')->name('propertytype');
    Route::post('/manageType', 'App\Http\Controllers\Propertytype@managePropertyType')->name('manageType');
    Route::post('/deleteType', 'App\Http\Controllers\Propertytype@deleteType')->name('deleteType');
    Route::post('/deleteMultiple', 'App\Http\Controllers\Propertytype@deleteMultiple')->name('deleteMultiple');
    Route::get('/pricerange', 'App\Http\Controllers\Pricerange@index')->name('pricerange');

});
