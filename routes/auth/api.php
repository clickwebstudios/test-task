<?php
Route::group(['namespace' => 'API\Auth'], function () {
  
    Route::post('auth/login', 'AuthController@login')
        ->name('auth.login');
  
    Route::post('auth/logout', 'AuthController@logout')
        ->name('auth.logout');
    
});
