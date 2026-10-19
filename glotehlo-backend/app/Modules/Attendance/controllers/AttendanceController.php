<?php

namespace App\Modules\Attendance\controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Modules\Attendance\services\AttendanceEventsService;




class AttendanceController extends Controller {

public function   __construct (
    private AttendanceEventsService $attendanceEventsService
    ){

}



    public function clock(Request $request)
    {
        $validatedData = $request->validate([
            'action'          => 'required|in:in,out',
            'qr_slug'         => 'required|string|max:40',
            'idempotency_key' => 'required|uuid',
            'device_token'    => 'nullable|string|max:100',
        ]);

        $validatedData['kind'] = $validatedData['action'];

        $user = $request->user();

        $clockRequest = $this->attendanceEventsService->clock(
            $user,
            $request->ip(),
            $validatedData
        );

        $statusCode = $clockRequest['already_recorded']
            ? 200
            : 201;

        return response()->json([
            'data' => $clockRequest,
        ], $statusCode);
    }



     public function getMyAttendanceToday(Request $request)
     {

          $user =  $request ->user () ;

          $attendance = $this -> attendanceEventsService -> getMyAttendanceToday ($user);




          return response()->json([
             'data' => $attendance,
          ], 200);

     }



     public function getAllMyAttendance (Request $request)
     {



        $user = $request -> user();

        $allMyAttendance = $this ->  attendanceEventsService -> getAllMyAttendance($user);


        return response()->json([
             $allMyAttendance
          ], 200);

     }




    public function getMyAttendanceSummary (Request $request)
    {
        $user = $request -> user();

        $summary = $this -> attendanceEventsService -> getMyAttendanceSummary($user);

        return response()->json([
            'data' => $summary,
          ], 200);

    }










}




















