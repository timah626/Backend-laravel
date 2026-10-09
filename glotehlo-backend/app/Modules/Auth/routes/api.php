<?php



use App\Modules\Auth\controllers\AuthController;

use Illuminate\Support\Facades\Route;




Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);


Route::middleware('web')->post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

