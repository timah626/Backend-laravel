<?php

namespace App\Modules\Users\routes;

use Illuminate\Support\Facades\Route;

use App\Modules\Users\Controllers\UserController;

use App\Modules\Users\Controllers\Admin\AdminUserController;


Route::middleware('auth:sanctum')
    ->get('/departments/{departmentId}/interns', [AdminUserController::class, 'getInterns']);




Route::middleware('auth:sanctum')

->get('/interns', [AdminUserController::class, 'listInterns']);




//another test route
Route::middleware('auth:sanctum')
 ->get('/users', [AdminUserController::class, 'searchUsers']) ;






Route::middleware('auth:sanctum')
 ->get('/interns/{id}', [AdminUserController::class, 'getInternById']) ;











//testRoute
Route::delete('/users/{email}', [AdminUserController::class, 'deleteUser']);



