<?php

use App\Modules\Attendance\controllers\AttendanceController;

use Illuminate\Support\Facades\Route;




Route::post('/clock-in', [AttendanceController::class, 'clockIn']);

Route::post('/clock-out', [AttendanceController::class, 'clockOut']);




