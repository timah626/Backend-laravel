<?php

namespace App\Modules\Auth\Controllers;

use  App\Http\Controllers\Controller;

use App\Modules\Auth\Services\AuthService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;



class AuthController extends Controller {

    public function __construct
    (
         private AuthService $authService
    )
        {} //dependec



    public function login (Request $request) {
         $validatedData = $request -> validate([

        'email' => 'required|email',
        'password' => 'required|min:8',
        ]);

         $user = $this -> authService -> login ($validatedData);


         $request->session()->regenerate();

         Log::info('SESSION COOKIE DEBUG', [
          'same_site' => config('session.same_site'),
          'secure' => config('session.secure'),
          'manager_same_site' => app('session')->getSessionConfig()['same_site'],
         ]);

         return response() -> json
         ([
            'data' => $user
         ], 200);


    }





    public function me(Request $request)

    {
        return response()->json($request->user());
    }



    public function logout (Request $request)

    {


        $this->authService->logout($request);

        return response () -> json ([
            'message' => 'user logged out '
            ], 204);


    }






    public function changePassword (Request $request)
     {

        $validatedData = $request -> validate ([
            'new_password'     => 'required|min:8',
            'confirm_password' => 'required|min:8',
            'current_password' => 'required|min:8'

        ]);


        $user = $request -> user();



        $changedPassword = $this -> authService -> changePassword ($user , $validatedData);


         return response () -> json ([

            'message' => 'PASSWORD_CHANGED SUCESSFULLY',

            ], 204);



     }









}
