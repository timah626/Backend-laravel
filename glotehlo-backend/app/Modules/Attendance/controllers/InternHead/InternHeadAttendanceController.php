<?php

namespace App\Modules\Attendance\controllers\InternHead;
use Illuminate\Http\Request;
use  App\Http\controllers\Controller;
use App\Modules\Attendance\services\InternHead\InternHeadAttendanceService;




class   InternHeadAttendanceController extends Controller {

   public function __construct(

        private  InternHeadAttendanceService  $internHeadAttendanceService
   ) {}

   public function getAttendancePerDepartment (Request $request) {


        $user = $request -> user();

        $attendancePerDepartment = $this -> internHeadAttendanceService -> getAttendancePerDepartmentToday ($user);


        return response()->json([
             'data' => $attendancePerDepartment,
          ], 200);



   }



}
