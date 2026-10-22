<?php

namespace App\Modules\Attendance\controllers\InternHead;
use Illuminate\Http\Request;
use  App\Http\Controllers\Controller;
use App\Modules\Attendance\services\InternHead\InternHeadAttendanceService;




class   InternHeadAttendanceController extends Controller {

    public function __construct(

        private  InternHeadAttendanceService  $internHeadAttendanceService
    ) {}

    public function getAttendancePerDepartment (Request $request) {


        $user = $request -> user();

        $attendancePerDepartment = $this -> internHeadAttendanceService -> getAttendancePerDepartmentToday ($user);


        return response()->json(
              $attendancePerDepartment, 200);



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


    public function confirmAttendance (Request $request , string $dayId)
    {

        $user = $request -> user();

        $confirmedAttendance = $this -> internHeadAttendanceService ->confirmAttendance($user, $dayId);

        return response()->json([
             'message' => 'attendance CONFIRMED',
             'data' => $confirmedAttendance,
        ], 200);


    }


    public function rejectAttendance (Request $request , string $dayId)
    {

        $user = $request -> user();

        $rejectedAttendance = $this -> internHeadAttendanceService ->rejectAttendance($user, $dayId);

        return response()->json([
             'message' => 'attendance REJECTED',
             'data' => $rejectedAttendance,
        ], 200);


    }


    public function markAsPresent (Request $request)
    {

        $validatedData = $request->validate([
            'intern_id' => 'required|exists:users,id',
            'note'      => 'nullable|string|max:255',
        ]);

        $user = $request -> user();

        $markedPresent = $this -> internHeadAttendanceService ->markAsPresent($user, $validatedData['intern_id'] ,$validatedData['note']?? null);

        return response()->json([
             'message' => 'marked Present',
             'data'    => $markedPresent,
        ], 200);


    }



}
