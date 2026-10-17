<?php

use App\Modules\Auth\Controllers\AuthController;

use App\Modules\Auth\Controllers\Admin\AdminAuthController;

use Illuminate\Support\Facades\Route;






Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);


Route::middleware('web')->post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->post('/auth/password', [AuthController::class, 'changePassword']);

Route::middleware('auth:sanctum')->post('/users/{id}/reset-password', [AdminAuthController::class, 'resetPassword']);

