<?php


use App\Modules\OfficeNetworks\controllers\OfficeNetworkController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')
    ->post('/sites/{siteId}/addnetworks', [OfficeNetworkController::class, 'addCurrentConnection']);



Route::middleware('auth:sanctum')
    ->post('/sites/{siteId}/allnetworks', [OfficeNetworkController::class, 'getAllNetworkBySite ']);















    
//claude's code // lets test this function to see the ip address of the user sending the request
    Route::get('/whoami-ip', function (Illuminate\Http\Request $request) {
    return response()->json([
        'ip' => $request->ip(),
        'forwarded_for' => $request->header('X-Forwarded-For'),
        'cf_ip' => $request->header('CF-Connecting-IP'),
    ]);
});
