<?php

use App\Modules\Attendance\controllers\AttendanceController;

use Illuminate\Support\Facades\Route;




Route::middleware('auth:sanctum')->post('/attendance/clock', [AttendanceController::class, 'clock']);



Route::middleware('auth:sanctum') -> get ('attendance/me/today', [AttendanceController::class, 'getMyAttendanceToday']);

