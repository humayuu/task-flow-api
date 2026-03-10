<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'UserRegister');
    Route::post('login', 'UserLogin');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'UserLogout']);


    Route::resource('user', UserController::class);
    Route::resource('task', TaskController::class);
});