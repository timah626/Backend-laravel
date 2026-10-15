<?php

use App\Modules\Organisation\controllers\OrganisationController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')
    ->post('/sites/{siteId}/departments', [OrganisationController::class, 'createDepartments']);





Route::middleware('auth:sanctum')
   ->post('/sites', [OrganisationController::class, 'createSites']);




Route::get('/departments', [OrganisationController::class, 'getDepartmentsbySite']);













