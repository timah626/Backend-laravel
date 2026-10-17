<?php


namespace App\Modules\Auth\Controllers\Admin;

use  App\Http\Controllers\Controller;

use Illuminate\Http\Request;


use App\Modules\Auth\Services\Admin\AdminAuthService;




class AdminAuthController extends Controller {


    public function __construct (


        private AdminAuthService $adminAuthService
    )
    {}


    public function resetPassword (Request $request , string $userId)
    {

        $user = $request -> user();


        $newPassword = $this -> adminAuthService -> resetPassword ( $user, $userId);

        return response()->json(
            [  'message' => 'okay, password changed sucessfully',
               'data' => $newPassword,
            ], 201);



    }



    }
