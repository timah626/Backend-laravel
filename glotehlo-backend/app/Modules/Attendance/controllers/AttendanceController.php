<?php

namespace App\Modules\Attendance\controllers;

use  App\Http\controllers\Controller;

use Illuminate\Http\Request;

use App\Modules\Attendance\services\AttendanceEventsService;




class AttendanceController extends Controller {

public function   __construct (
    private AttendanceEventsService $attendanceEventsService
    ){

}



public  function clockIn(Request $request  )  {

      $validatedData = $request->validate([
         'email'          => 'required|email',
         'slug'           => 'required|string|max:40',
         'idempotencyKey' => 'required|uuid',
         'deviceToken'    => 'nullable|string|max:100',
          ]);


       $validatedData['kind'] = 'in';

  $clockinRequest =  $this -> attendanceEventsService ->clock  (
        $request -> ip(),
        $validatedData,


  );

    $statusCode = $clockinRequest['already_recorded']
    ? 200
    : 201;

     return response() -> json([

        'data' => $clockinRequest,

    ], $statusCode);



}



public  function clockOut(Request $request  )  {



      $validatedData = $request->validate([
         'email'          => 'required|email',
         'slug'           => 'required|string|max:40',
         'idempotencyKey' => 'required|uuid',
         'deviceToken'    => 'nullable|string|max:100',
          ]);


       $validatedData['kind'] = 'out';



  $clockinRequest =  $this -> attendanceEventsService ->clock (
        $request -> ip(),
        $validatedData,


  );

    $statusCode = $clockinRequest['already_recorded']
    ? 200
    : 201;

     return response() -> json([

        'data' => $clockinRequest,

    ], $statusCode);



}






















}
