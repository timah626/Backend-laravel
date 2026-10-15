<?php



namespace App\Modules\Onboarding\Controllers;


use  App\Http\Controllers\Controller;

use  App\Modules\Onboarding\Services\ApplicationService;
use Illuminate\Http\Request;


class ApplicationController extends Controller {


  public function __construct (

    private ApplicationService $applicationService,

  )  {}



    public function submitApplications (Request $request) {

       $validatedData = $request -> validate([

            'first_name'                 =>'required|string|max:100',
            'last_name'                  => 'required|string|max:100',
            'email'                      => 'required|email',
            'major'                      => 'required|string',
            'school'                     => 'required',
            'level'                      => 'required',
            'suggested_start_date'       => 'required|date',
            'suggested_end_date'         => 'required|date|after:suggested_start_date',
            'cv'                         => 'required|url',
            'gender'                     => 'required|string|in:male,female',
            'suggested_department_id'    => 'required|exists:departments,id',
            'handwritten_application'    => 'nullable|url',
            'recommendation_letter'      => 'nullable|url',
            'attestation_of_attendance'  => 'nullable|url',
            'profile_picture'            => 'nullable|url',


        ]);



        $applications  =   $this -> applicationService -> application($validatedData);

        $statusCode = $applications['already_submitted']
          ? 200
          : 201;

        $code = $applications['already_submitted'] ? 'ALREADY_SUBMITTED ': 'APPLICATION_RECEIVED';





        return response() -> json([

             'message_code'=> $code,
             'data'   => $applications

            ], $statusCode);
    }






}
