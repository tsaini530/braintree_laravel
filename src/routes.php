<?php 
Route::group(['middleware' => ['web','auth']] , function(){
	
	Route::resource('/braintree', 'PaymentController');


});