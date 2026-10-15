<?php

use App\Modules\Onboarding\Controllers\ApplicationController;

use App\Modules\Onboarding\Controllers\Admin\ApplicationController as AdminApplicationController;

use Illuminate\Support\Facades\Route;


Route::post('/applications',
 [ApplicationController::class, 'submitApplications']);


Route::middleware('auth:sanctum')->get('/applications',
 [AdminApplicationController::class, 'getApplications']);


Route::middleware('auth:sanctum')->post('/applications/{applicationId}/approve', [AdminApplicationController::class, 'approveApplication']);

Route::middleware('auth:sanctum')->post('/applications/{applicationId}/reject', [AdminApplicationController::class, 'rejectApplication']);


Route::middleware('auth:sanctum')->delete('/applications/{applicationId}',
[AdminApplicationController::class, 'deleteApplication']);


Route::middleware('auth:sanctum')->get('/applications/{applicationId}',
[AdminApplicationController::class, 'getApplicationById']);


Route::middleware('auth:sanctum')->get('/applications/departments/{departmentId}',
[AdminApplicationController::class, 'getApplicationsByDepartment']);





