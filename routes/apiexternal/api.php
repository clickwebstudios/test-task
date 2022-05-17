<?php
Route::group(['namespace' => 'ApiExternal', 'middleware' => ['AuthAdminOrToken']], function () {
  
    Route::get('metadata', 'ApiExternalController@metadata')
        ->name('metadata');
    
});
