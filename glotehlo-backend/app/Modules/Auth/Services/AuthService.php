<?php

namespace App\Modules\Auth\Services;

use App\Modules\Users\Repository\Admin\AdminRepository;

use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Auth;

use App\Exceptions\AppException;

use Illuminate\Support\Facades\RateLimiter;




class AuthService {
  public function __construct (
    private AdminRepository $adminRepository
  ) {

  }


 public function login (array $data) {
    $user = $this -> adminRepository-> findByEmail ($data ['email']);

    if ( !$user) {
        throw new AppException(
          'INVALID_CREDENTIALS',
          'invalid email or  password',
           401
);
    }

    if  (!Hash::check($data['password'], $user->password)) {
         throw new AppException(
          'INVALID_CREDENTIALS',
          'invalid email or password',
           401
);
    }


     Auth::login($user);




      return ['user' => $user];


    }






    public function logout ( $request ) {


      Auth::guard('web')->logout();

      $request -> session() -> invalidate ();

      $request -> session() -> regenerateToken();





        response()->noContent();

    }





  /* public function changePassword ( $user, array $validatedData)
     {

        if ($validatedData['new_password'] === $validatedData['confirm_password'])

        {
            $newPassword = $this -> adminRepository -> setnewPassword($user);
        }


    } */


}








