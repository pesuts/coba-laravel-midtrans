<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nona', function(){
  return view('nona');
});

Route::get('/payment', [PaymentController::class, 'index']);

Route::post('/payment/charge', [PaymentController::class, 'charge']);

Route::post('/payment/notification', [PaymentController::class, 'notification']);
