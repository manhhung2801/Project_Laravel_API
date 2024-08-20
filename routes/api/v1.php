<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Api\V1\AuthController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// register verify email token
Route::post('/email-verify', [AuthController::class, 'verifyEmail'])->name('user.verify');
// send mail
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/confirm-change-password', [AuthController::class, 'confirmChangePassword']);
// send mail
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group( function () {
    Route::get('posts', [PostController::class, 'getPosts']);
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
    Route::post('posts/upload-image', [PostController::class, 'addImage']);
});
