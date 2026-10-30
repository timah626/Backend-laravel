<?php

namespace App\Modules\Users\Controllers\Admin;

use  App\Http\Controllers\Controller;

use App\Modules\Users\Services\Admin\AdminUserService;

use Illuminate\Http\Request;








class AdminUserController extends Controller {

    public  function __construct (

        private AdminUserService $adminUserService
    ){}



    public function getInterns(Request $request, string $departmentId)

    {
        $interns = $this->adminUserService->getInternsByDepartment(
             $request->user(),
             $departmentId
    );

         return response()->json(['data' => $interns], 200);
    }



  //test controller
    public function deleteUser(Request $request, string $email)
    {
        $this->adminUserService->deleteUser($email);

        return response()->json(null, 204);
    }




    public function searchUsers  (Request $request )
     {



        $validatedData = $request->validate([
        'search' => ['required', 'string', 'min:2'],
        ]);


        $user = $request -> user();
        $term = $validatedData['search'];
        $page = $request->query('page', 1);      


        $searchResult = $this -> adminUserService -> searchUser ($user, $term);

        return response()->json(
            [  'message' => 'okay, returned',
               'data' => $searchResult
            ], 200);




    }














     public function listInterns(Request $request)
     {
        $filters = $request->validate([
            'site_id'                => 'nullable', 'integer', 'exists:sites,id',
            'department_id'          => 'nullable', 'integer', 'exists:departments,id',
            'search'                 => 'nullable', 'string', 'max:40',
            'page'                   => 'nullable', 'integer', 'min:1',
            'per_page'               => 'nullable', 'integer', 'min:1', 'max:50',
        ]);

        $page = $this->adminUserService->listInterns(
            $request->user(),
            $filters
        );

        return response()->json($page, 200);
     }





    public function getInternById( Request $request, string $internId)
     {

        $user = $request -> user ();

        $interns = $this -> adminUserService -> getInternById($user, $internId);


         return response()->json(
            [  'message' => 'take your stupid interns',
               'data' => $interns,
            ], 201);



     }














}



































