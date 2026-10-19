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

    public function departmentHistory(Request $request)
    {


        $validatedData = $request->validate([
            'from'      => 'nullable|date',
            'to'        => 'nullable|date|after_or_equal:from',
            'intern_id' => 'nullable',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ]);


        $user = $request -> user();



        $result = $this->internHeadAttendanceService->getDepartmentHistory($user,  $validatedData);

        return response()->json([
             'data' => $result,
        ], 200);
    }



}
