<?php



namespace App\Modules\Users\routes;

use Illuminate\Support\Facades\Route;


use App\Modules\Users\Controller\UserController;



Route::middleware('auth:sanctum')
    ->get('/departments/{departmentId}/interns', [UserController::class, 'getInterns']);



    


