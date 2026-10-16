<?php

namespace App\Modules\Users\routes;

use Illuminate\Support\Facades\Route;

use App\Modules\Users\Controllers\UserController;

use App\Modules\Users\Controllers\Admin\AdminUserController;


Route::middleware('auth:sanctum')
    ->get('/departments/{departmentId}/interns', [AdminUserController::class, 'getInterns']);



Route::middleware('auth:sanctum')
 ->get('/users', [AdminUserController::class, 'searchUsers']) ;




Route::middleware('auth:sanctum')
 ->post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword']) ;












//testRoute
Route::delete('/users/{email}', [AdminUserController::class, 'deleteUser']);



