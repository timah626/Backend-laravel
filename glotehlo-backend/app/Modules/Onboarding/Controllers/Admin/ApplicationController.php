<?php


namespace App\Modules\Onboarding\Controllers\Admin;


use App\Modules\Onboarding\Services\Admin\ApplicationService;

use  App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApplicationController extends Controller {



    public function __construct(

          private ApplicationService $applicationService,
 ) {}


    public function getApplications(Request $request)

     {
        $validatedData = $request->validate([
             'status' => 'required|in:pending,rejected,approved',
             ]);

        $applications = $this->applicationService->getApplicationByStatus(
             $request->user(),
             $validatedData['status']
            );

         return response()->json($applications);
     }


    public function approveApplication (Request $request  ,  string $applicationId)
        {


             $validatedData = $request->validate

             ([
                 'first_name'    => 'nullable|string|max:100',

                 'last_name'     => 'nullable|string|max:100',

                 'email'         => 'nullable|email',

                 'school'        => 'nullable|string',

                 'gender'        => 'required|string|in:male,female',

                 'level'         => 'nullable|string',

                 'major'         => 'nullable|string',

                 'department_id' => 'nullable|exists:departments,id',

                 'start_date'    => 'nullable|date',

                 'end_date'      => 'nullable|date|after:start_date',

                ]);


            $user = $request -> user ();


            $application = $this -> applicationService -> approveApplication(
                $user,
                $applicationId,
                $validatedData
            );


            $statusCode = $application['already_approved']
                ? 200
                : 201;

            return response()->json
             ([
                 'data' =>
                     [
                         'user' => $application['user'],
                         'temporary_password' => $application['temporary_password'],
                     ],
             ], $statusCode);

        }




    public function rejectApplication (Request $request , string $applicationId )
    {
       $user = $request -> user();

       $application = $this -> applicationService -> rejectApplication($user,  $applicationId );

       return response()->json
       ([
           'message' => 'application deleted succesflly',
           'data'    => $application
       ], 201);
    }






    //debug routes  . just leave it sha
    public function deleteApplication (Request $request, string $applicationId )

        {
             $this -> applicationService -> deleteApplication( $applicationId);

             return  response()->json(['message' => 'deleted']);
        }



    public function getApplicationById (Request $request ,   string $applicationId)
       {

            $user = $request -> user ();

            $anApplication = $this -> applicationService-> getApplicationById ($user , $applicationId);

            return response()->json(
            [  'message' => 'okay, returned',
               'data' => $anApplication
            ], 200);


      }


    public function getApplicationsByDepartment ( Request $request  ,  string $departmentId)
    {

            $user = $request -> user ();

            $applications = $this -> applicationService-> getApplicationsByDepartment ($user , $departmentId);

            return
            [  'message' => 'okay, returned',
               'data' => $applications
            ];
    }




   /* public function searchUsers  (Request $request ) {

        $user = $request -> user();
        $term = $request->query('search');
        $page = $request->query('page', 1);      //one is kinda like the default page number .. you know how anoyying  users are, gota be rechecking and putting default values everywhere


        $validatedData = $request->validate([


        ])

        $searchResult = $this -> applicationService -> searchUser ($user, $term, $page);

          return response()->json(
            [  'message' => 'okay, returned',
               'data' => $searchResult
            ], 200);




    }
   */







}

