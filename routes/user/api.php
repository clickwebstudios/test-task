<?php
Route::group(['namespace' => 'API\User', 'middleware' => ['AuthAdmin']], function () {
  
    Route::post('user/checkoutCoins', 'UserController@checkoutCoins')
        ->name('user.checkoutCoins');
    
    Route::post('user/generateAccessToken', 'UserController@generateAccessToken')
        ->name('user.generateAccessToken');
    
    Route::post('user/logs', 'UserController@logs')
        ->name('user.logs');
});
