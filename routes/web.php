<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify');