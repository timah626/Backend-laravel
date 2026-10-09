<?php

namespace App\Modules\Users\Controller;

use  App\Http\Controllers\Controller;

use App\Modules\Users\Services\UserService;

use Illuminate\Http\Request;




class UserController extends Controller {

public  function __construct (
    private UserService $userService 
){}



public function getInterns(Request $request, string $departmentId)
{
    $interns = $this->userService->getInternsByDepartment(
        $request->user(),
        $departmentId
    );

    return response()->json(['data' => $interns], 200);
}





public function createAccount(Request $request, array $draft)
{

     $validatedData = $request -> validate([

      'email' => 'required
      |email',
      'school' => 'required',
      'level' => 'required',
      'departmentname' => 'required'


       
    ]);

    $newInterns = $this->userService->createAccount(  $validatedData );

    return response()->json(['data' => $newInterns], 200);
}


}















