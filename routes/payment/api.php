<?php
Route::group(['namespace' => 'API\Payment', 'middleware' => ['AuthAdmin']], function () {
  
    Route::post('auth/payment/checkout', 'PaymentController@checkout')
        ->name('payment.checkout');
      
    Route::get('auth/payment/getPayments', 'PaymentController@getPayments')
        ->name('payment.getPayments');
    
    Route::post('auth/payment/store', 'PaymentController@store')
        ->name('payment.store');
    
    Route::post('auth/payment/setDefault/{userPayment}', 'PaymentController@setDefault')
        ->name('payment.setDefault');
    
    Route::post('auth/payment/delete/{userPayment}', 'PaymentController@delete')
        ->name('payment.delete');
    
});
