<?php

namespace App\Modules\Auth\controllers;

use  App\Http\Controllers\Controller;

use App\Modules\Auth\Services\AuthService;

use Illuminate\Http\Request;



class AuthController extends Controller {

public function __construct (
    private AuthService $authService
)
{} //dependec



public function login (Request $request) {
    $validatedData = $request -> validate([

      'email' => 'required
      |email',
      'password' => 'required|min:8',
    ]);

    $user = $this -> authService -> login ($validatedData);


    $request->session()->regenerate();

    return response() -> json([
        'data' => $user
    ], 200);


}





public function me(Request $request)
{
    return response()->json($request->user());
}



public function logout (Request $request) {


    $this->authService->logout($request);

 return response () -> json ([
    'message' => 'user logged out '
 ], 200);
}


}