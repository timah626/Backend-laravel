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
        $page = $request->query('page', 1);      //one is kinda like the default page number .. you know how anoyying  users are, gota be rechecking and putting default values everywhere


        $searchResult = $this -> adminUserService -> searchUser ($user, $term);

        return response()->json(
            [  'message' => 'okay, returned',
               'data' => $searchResult
            ], 200);




    }




    public function resetPassword (Request $request , string $userId)
     {

        $user = $request -> user();


        $newPassword = $this -> adminUserService -> resetPassword ( $user, $userId);

        return response()->json(
            [  'message' => 'okay, password changed sucessfully',
               'data' => $newPassword,
            ], 201);



     }


















}















