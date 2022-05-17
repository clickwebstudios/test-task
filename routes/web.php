<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin', 'middleware' => ['web', 'AuthAdmin']], function() {
    
    Route::get('/{any?}', 'Admin\AdminController@index')
        ->where('any', '[\/\w\.-]*')
        ->name('admin.index');
  
});

Route::get('/login', [
    'uses' => 'Admin\AuthController@index', 
    'as' => 'auth.index'
]);