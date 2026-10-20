<?php

use App\Modules\Attendance\controllers\AttendanceController;

use App\Modules\Attendance\controllers\InternHead\InternHeadAttendanceController;

use Illuminate\Support\Facades\Route;




Route::middleware('auth:sanctum')->post('/attendance/clock', [AttendanceController::class, 'clock']);



Route::middleware('auth:sanctum') -> get ('attendance/me/today', [AttendanceController::class, 'getMyAttendanceToday']);


Route::middleware('auth:sanctum') -> get ('attendance/me', [AttendanceController::class, 'getAllMyAttendance']);

Route::middleware('auth:sanctum') -> get ('attendance/me/summary', [AttendanceController::class, 'getMyAttendanceSummary']);

Route::middleware('auth:sanctum') -> get ('attendance/department/today', [InternHeadAttendanceController::class, 'getAttendancePerDepartment']);


Route::middleware('auth:sanctum') -> get ('attendance/department', [InternHeadAttendanceController::class, 'departmentHistory']);


Route::middleware('auth:sanctum') -> get ('attendance/department', [InternHeadAttendanceController::class, 'departmentHistory']);


Route::middleware('auth:sanctum') -> post('attendance/days/{id}/confirm', [InternHeadAttendanceController::class, 'confirmAttendance']);


Route::middleware('auth:sanctum') -> post('attendance/mark-present', [InternHeadAttendanceController::class, 'markAsPresent']);
















