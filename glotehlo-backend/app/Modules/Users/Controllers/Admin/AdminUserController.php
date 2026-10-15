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



    public function resetPassword (Request $request ) {

    
    }


















}















