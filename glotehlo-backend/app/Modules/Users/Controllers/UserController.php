<?php

namespace App\Modules\Users\Controllers;

use  App\Http\Controllers\Controller;

use App\Modules\Users\Services\UserService;

use Illuminate\Http\Request;




class UserController extends Controller {

    public  function __construct (


    ){}



    public function changePassword (Request $request)

    {

        $validatedData =$request -> validate([
          'newPassword' => 'required|min:8',
        ]);


        $password = $validatedData ['newPassword'];



        //$newPassword = $this ->





    }
























}















