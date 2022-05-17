<?php
Route::group(['namespace' => 'API\Store', 'middleware' => ['AuthAdmin']], function () {
  
    Route::get('/store/global/get', 'StoreGlobalController@get')
        ->name('store.global.get');
  
});
